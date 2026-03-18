<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Loan;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_prestado'   => Loan::sum('monto'),
            'total_cobrado'    => Payment::sum('monto'),
            'prestamos_activos' => Loan::where('estado', 'activo')->count(),
            'prestamos_atrasados' => Loan::where('estado', 'atrasado')->count(),
            'en_cobranza'      => Loan::where('estado', 'en_cobranza')->count(),
            'en_legal'         => Loan::where('estado', 'legal')->count(),
            'clientes_total'   => Client::where('activo', true)->count(),
        ];

        $stats['ganancia'] = Loan::all()->sum('total_intereses');
        $stats['saldo_pendiente'] = $stats['total_prestado'] - $stats['total_cobrado'];

        $prestamos_recientes = Loan::with('client')
            ->latest()
            ->limit(10)
            ->get();

        $pagos_recientes = Payment::with(['loan.client', 'usuario'])
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard', compact('stats', 'prestamos_recientes', 'pagos_recientes'));
    }
}
