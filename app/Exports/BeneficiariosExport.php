<?php

namespace App\Exports;

use App\Models\Beneficiario;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BeneficiariosExport implements 
    FromQuery, 
    WithHeadings, 
    WithMapping,
    WithStyles,
    ShouldAutoSize
{
    protected $filtros;

    public function __construct(array $filtros = [])
    {
        $this->filtros = $filtros;
    }

    public function query()
    {
        $query = Beneficiario::query();

        if (!empty($this->filtros['nome'])) {
            $query->where('nome', 'like', '%' . $this->filtros['nome'] . '%');
        }

        if (!empty($this->filtros['social_id'])) {
            $query->where('social_id', $this->filtros['social_id']);
        }

        if (!empty($this->filtros['municipio'])) {
            $query->where('municipio', $this->filtros['municipio']);
        }

        if (!empty($this->filtros['bairro'])) {
            $query->where('bairro', $this->filtros['bairro']);
        }

        if (isset($this->filtros['pago']) && $this->filtros['pago'] !== '') {
            $query->where('pago', $this->filtros['pago']);
        }

        if (!empty($this->filtros['sexo'])) {
            $query->where('sexo', $this->filtros['sexo']);
        }

        return $query->orderBy('nome');
    }

    public function headings(): array
    {
        return [
            'Social ID',
            'Nome',
            'Município',
            'Bairro',
            'Observações',
        ];
    }

    public function map($beneficiario): array
    {
        $valorPago = $beneficiario->rec1 + $beneficiario->rec2 + $beneficiario->rec3
                   + $beneficiario->rec4 + $beneficiario->rec5 + $beneficiario->rec6;

        return [
            $beneficiario->social_id,
            $beneficiario->nome,
            $beneficiario->municipio,
            $beneficiario->bairro ?? '',
            $beneficiario->observacoes ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Cabeçalho em negrito com fundo azul
            1 => [
                'font'    => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'    => ['fillType' => 'solid', 'startColor' => ['rgb' => '0D6EFD']],
            ],
        ];
    }
}