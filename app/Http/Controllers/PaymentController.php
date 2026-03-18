<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['loan.client', 'usuario'])->latest('fecha_pago');

        if ($request->filled('fecha_desde')) {
            $query->where('fecha_pago', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->where('fecha_pago', '<=', $request->fecha_hasta);
        }

        $payments = $query->paginate(20)->withQueryString();

        return view('payments.index', compact('payments'));
    }

    public function create(Loan $loan)
    {
        $loan->load(['client', 'installments' => function ($q) {
            $q->where('pagado', false)->orderBy('numero_cuota');
        }]);

        return view('payments.create', compact('loan'));
    }

    public function store(Request $request, Loan $loan)
    {
        $data = $request->validate([
            'installment_id' => 'nullable|exists:installments,id',
            'monto'          => 'required|numeric|min:1',
            'fecha_pago'     => 'required|date',
            'metodo'         => 'required|in:efectivo,transferencia,cheque,otro',
            'referencia'     => 'nullable|string|max:100',
            'notas'          => 'nullable|string',
        ]);

        DB::transaction(function () use ($data, $loan) {
            $data['loan_id']    = $loan->id;
            $data['usuario_id'] = Auth::id();

            $payment = Payment::create($data);

            // Marcar cuota como pagada si se especificó
            if (!empty($data['installment_id'])) {
                Installment::find($data['installment_id'])->update([
                    'pagado'     => true,
                    'fecha_pago' => $data['fecha_pago'],
                ]);
            }

            // Actualizar estado del préstamo si está saldado
            $totalPagado = $loan->payments()->sum('monto');
            if ($totalPagado >= $loan->total_a_pagar) {
                $loan->update(['estado' => 'cerrado']);
            }
        });

        return redirect()->route('loans.show', $loan)
            ->with('success', 'Pago registrado exitosamente.');
    }

    public function destroy(Payment $payment)
    {
        $loan = $payment->loan;
        $payment->delete();

        return redirect()->route('loans.show', $loan)
            ->with('success', 'Pago eliminado.');
    }
}
