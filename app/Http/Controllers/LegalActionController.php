<?php

namespace App\Http\Controllers;

use App\Models\LegalAction;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LegalActionController extends Controller
{
    public function index()
    {
        $loans = Loan::with(['client', 'legalActions'])
            ->whereIn('estado', ['en_cobranza', 'legal', 'atrasado'])
            ->latest()
            ->paginate(20);

        return view('legal.index', compact('loans'));
    }

    public function create(Loan $loan)
    {
        $loan->load('client');
        $tipos = LegalAction::tipos();
        return view('legal.create', compact('loan', 'tipos'));
    }

    public function store(Request $request, Loan $loan)
    {
        $data = $request->validate([
            'tipo'        => 'required|in:notificacion,llamada,visita,carta,demanda,acuerdo_pago,otro',
            'descripcion' => 'required|string',
            'fecha'       => 'required|date',
            'resultado'   => 'nullable|string|max:255',
        ]);

        $data['loan_id']    = $loan->id;
        $data['usuario_id'] = Auth::id();

        LegalAction::create($data);

        // Actualizar estado si se registra una demanda
        if ($data['tipo'] === 'demanda' && $loan->estado !== 'legal') {
            $loan->update(['estado' => 'legal']);
        }

        return redirect()->route('loans.show', $loan)
            ->with('success', 'Acción legal registrada.');
    }

    public function destroy(LegalAction $legalAction)
    {
        $loan = $legalAction->loan;
        $legalAction->delete();

        return redirect()->route('loans.show', $loan)
            ->with('success', 'Registro eliminado.');
    }
}
