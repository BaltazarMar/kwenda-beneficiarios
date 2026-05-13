<?php

namespace App\Imports;

use App\Models\BeneficiarioUrbano;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Carbon\Carbon;

class BeneficiariosUrbanoImport implements
    ToModel,
    WithHeadingRow,
    SkipsOnError,
    SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    public function model(array $row)
    {
        $identificador = trim($row['identificador'] ?? '');

        if (empty($identificador)) return null;

        return BeneficiarioUrbano::firstOrCreate(
    ['identificador' => $identificador],
    [
        'nome'             => $row['nome_completo'] ?? null,
        'sexo'             => $this->convertSexo($row['sexo'] ?? ''),
        'ip1'              => $row['ip1'] ?? null,
        'data_nascimento'  => $this->parseData($row['data_de_nascimento'] ?? null),
        'tipo_documento'   => $row['tipo_de_documento'] ?? null,
        'numero_documento' => $row['do_documento'] ?? null,
        'municipio'        => $row['municipio'] ?? null,
        'bairro'           => $row['bairro'] ?? null,
        'categoria'        => $row['categoria'] ?? null,
        'observacao'       => $row['observacao'] ?? null,
    ]
);
    }

    private function convertSexo($valor)
    {
        $valor = strtolower(trim($valor));
        if ($valor == 'masculino') return 'M';
        if ($valor == 'feminino')  return 'F';
        return null;
    }

    private function parseData($valor)
    {
        if (empty($valor)) return null;
        try {
            return Carbon::parse($valor)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}