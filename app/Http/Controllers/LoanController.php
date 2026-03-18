<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Installment;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $query = Loan::with('client')->latest();

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->whereHas('client', function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('cedula', 'like', "%{$buscar}%");
            });
        }

        $loans = $query->paginate(20)->withQueryString();

        return view('loans.index', compact('loans'));
    }

    public function create()
    {
        $clients = Client::where('activo', true)->orderBy('nombre')->get();
        return view('loans.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id'       => 'required|exists:clients,id',
            'monto'           => 'required|numeric|min:100',
            'interes'         => 'required|numeric|min:0|max:100',
            'tipo_interes'    => 'required|in:mensual,anual',
            'plazo'           => 'required|integer|min:1|max:360',
            'frecuencia'      => 'required|in:diario,semanal,quincenal,mensual',
            'fecha_inicio'    => 'required|date',
            'mora_porcentaje' => 'required|numeric|min:0|max:50',
            'notas'           => 'nullable|string',
        ]);

        $data['usuario_id'] = Auth::id();
        $data['estado'] = 'activo';

        DB::transaction(function () use ($data) {
            $loan = Loan::create($data);
            $this->generarCuotas($loan);
        });

        return redirect()->route('loans.index')
            ->with('success', 'Préstamo creado y cuotas generadas exitosamente.');
    }

    public function show(Loan $loan)
    {
        $loan->load(['client', 'installments', 'payments.usuario', 'legalActions.usuario']);
        return view('loans.show', compact('loan'));
    }

    public function edit(Loan $loan)
    {
        if ($loan->estado === 'cerrado') {
            return back()->with('error', 'No se puede editar un préstamo cerrado.');
        }
        $clients = Client::where('activo', true)->orderBy('nombre')->get();
        return view('loans.edit', compact('loan', 'clients'));
    }

    public function update(Request $request, Loan $loan)
    {
        $data = $request->validate([
            'estado' => 'required|in:activo,atrasado,en_cobranza,legal,cerrado',
            'notas'  => 'nullable|string',
            'mora_porcentaje' => 'required|numeric|min:0|max:50',
        ]);

        $loan->update($data);

        return redirect()->route('loans.show', $loan)
            ->with('success', 'Préstamo actualizado.');
    }

    public function destroy(Loan $loan)
    {
        if ($loan->payments()->exists()) {
            return back()->with('error', 'No se puede eliminar un préstamo con pagos registrados.');
        }

        $loan->delete();

        return redirect()->route('loans.index')
            ->with('success', 'Préstamo eliminado.');
    }

    public function contrato(Loan $loan)
    {
        $loan->load('client');
        return view('loans.contrato', compact('loan'));
    }

    private function generarCuotas(Loan $loan): void
    {
        $cuota = $loan->calcularCuota();
        $fechaBase = Carbon::parse($loan->fecha_inicio);

        for ($i = 1; $i <= $loan->plazo; $i++) {
            $fechaVencimiento = match ($loan->frecuencia) {
                'diario'    => $fechaBase->copy()->addDays($i),
                'semanal'   => $fechaBase->copy()->addWeeks($i),
                'quincenal' => $fechaBase->copy()->addDays($i * 15),
                'mensual'   => $fechaBase->copy()->addMonths($i),
            };

            Installment::create([
                'loan_id'           => $loan->id,
                'numero_cuota'      => $i,
                'fecha_vencimiento' => $fechaVencimiento,
                'monto'             => round($cuota, 2),
                'mora'              => 0,
                'pagado'            => false,
            ]);
        }
    }
}
