<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Beneficiario; // ← faltava esta linha

class DashboardController extends Controller
{
    public function dashboard()
    {
        $total        = Beneficiario::count();
        $pagos        = Beneficiario::where('pago', 1)->count();
        $naoPagos     = Beneficiario::where('pago', 0)->count();
        $nuncaPagos   = Beneficiario::where('pago', 2)->count();
        $masculino    = Beneficiario::where('sexo', 'M')->count();
        $feminino     = Beneficiario::where('sexo', 'F')->count();

        $valorTotal   = Beneficiario::selectRaw('
            SUM(rec1 + rec2 + rec3 + rec4 + rec5 + rec6) as total
        ')->value('total');

        $porMunicipio = Beneficiario::selectRaw('municipio, COUNT(*) as total')
            ->groupBy('municipio')
            ->orderByDesc('total')
            ->pluck('total', 'municipio');

        $recorrenciasPorAno = Beneficiario::selectRaw('
            YEAR(data1) as ano,
            SUM(rec1 + rec2 + rec3 + rec4 + rec5 + rec6) as total
        ')
        ->whereNotNull('data1')
        ->groupBy('ano')
        ->orderBy('ano')
        ->pluck('total', 'ano');

        return view('kwenda.dashboard', compact(
            'total',
            'pagos',
            'naoPagos',
            'nuncaPagos',
            'masculino',
            'feminino',
            'valorTotal',
            'porMunicipio',
            'recorrenciasPorAno'
        ));
    }
}