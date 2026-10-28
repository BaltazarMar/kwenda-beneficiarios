<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VerificacaoExport implements FromArray, WithHeadings, ShouldAutoSize
{
    public function __construct(private array $linhas)
    {
    }

    public function headings(): array
    {
        return [
            'Linha', 'Status', 'Encontrado em', 'Nome', 'Data de Nascimento',
            'Sexo', 'Bairro', 'Zona', 'Telefone', 'Categoria',
        ];
    }

    public function array(): array
    {
        $labels = ['novo' => 'Novo', 'possivel' => 'Possível', 'duplicado' => 'Duplicado'];

        return array_map(fn ($l) => [
            $l['linha'],
            $labels[$l['status']] ?? $l['status'],
            $l['encontrado_em'],
            $l['nome'],
            $l['data_nascimento'] ?? $l['data_original'],
            $l['sexo'],
            $l['bairro'],
            $l['zona'],
            $l['telefone'],
            $l['categoria'],
        ], $this->linhas);
    }
}