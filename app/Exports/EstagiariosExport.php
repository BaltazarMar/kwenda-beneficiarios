<?php

namespace App\Exports;

use App\Models\Estagiario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EstagiariosExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Estagiario::select('nome', 'sexo', 'bi', 'data_nascimento', 'estado')->get();
    }

    public function headings(): array
    {
        return [
            'Nome',
            'Sexo',
            'BI',
            'Data de Nascimento',
            'Estado'
        ];
    }
}
