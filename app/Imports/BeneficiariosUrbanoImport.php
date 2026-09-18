<?php

namespace App\Imports;

use App\Models\BeneficiarioUrbano;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Carbon\Carbon;

class BeneficiariosUrbanoImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts,
    SkipsOnError,
    SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function model(array $row)
    {
        $identificador = trim($row['social_id'] ?? '');
        if (empty($identificador)) return null;

        return BeneficiarioUrbano::updateOrCreate(
            ['identificador' => $identificador],
            [
                // ===== CAMPOS ORIGINAIS =====
                'nome'             => $row['nome_completo'] ?? $row['nome'] ?? $row['beneficiario'] ?? null,
                'sexo'             => $this->convertSexo($row['sexo'] ?? ''),
                'ip1'              => $row['ip1'] ?? null,
                'data_nascimento'  => $this->parseData($row['data_de_nascimento'] ?? $row['data_nascimento'] ?? null),
                'tipo_documento'   => $row['tipo_de_documento'] ?? $row['tipo_documento'] ?? null,
                'numero_documento' => $row['no_do_documento'] ?? $row['numero_documento'] ?? $row['nº_do_documento'] ?? null,
                'municipio'        => $row['municipio'] ?? $row['município'] ?? null,
                'bairro'           => $row['bairro'] ?? null,
                'categoria'        => trim($row['categoria'] ?? '') ?: null,
                'observacao'       => $row['observacao'] ?? $row['observação'] ?? $row['obs'] ?? null,

                // ===== NOVOS CAMPOS =====
                'social_id'              => $row['social_id'] ?? null,
                'numero_da_conta'        => $row['numero_da_conta'] ?? $row['número_da_conta'] ?? null,
                'numero_administrativo'  => $row['numero_administrativo'] ?? $row['número_administrativo'] ?? null,
                'card_id'                => $row['card_id'] ?? null,
                'telefone'               => $row['telefone'] ?? null,
                'agencia'                => $row['agencia'] ?? $row['agência'] ?? null,
                'beneficiario'           => $row['beneficiario'] ?? $row['nome'] ?? null,
                'contacto'               => $row['contacto'] ?? $row['telefone'] ?? null,
                'profissao'              => $row['profissao'] ?? $row['profissão'] ?? null,
                'provincia_residencia'   => $row['provincia_residencia'] ?? $row['província_residência'] ?? null,
                'municipio_residencia'   => $row['municipio_residencia'] ?? $row['município_residência'] ?? null,
                'comuna'                 => $row['comuna'] ?? null,
                'data_inscricao'         => $this->parseData($row['data_inscricao'] ?? $row['data_de_inscricao'] ?? null),
                'pago'                   => $this->parsePago($row['pago'] ?? null),
                'valor1'                 => $this->parseDecimal($row['valor1'] ?? null),
                'data1'                  => $this->parseData($row['data1'] ?? null),
                'rece_valor_agregado'    => $this->parseDecimal($row['rece_valor_agregado'] ?? null),
                'nome_valor_agregado'    => $row['nome_valor_agregado'] ?? null,
                'coordenada_bancaria'    => $row['coordenada_bancaria'] ?? $row['coordenada_bancária'] ?? null,
            ]
        );
    }

    /**
     * Converte sexo (Masculino/Feminino para M/F)
     */
    private function convertSexo($valor)
    {
        $valor = strtolower(trim($valor));
        if ($valor == 'masculino') return 'M';
        if ($valor == 'feminino')  return 'F';
        return null;
    }

    /**
     * Parseia datas em vários formatos
     */
    private function parseData($valor)
    {
        if (empty($valor)) return null;
        
        // Se é um número (timestamp do Excel)
        if (is_numeric($valor)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($valor)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        try {
            return Carbon::parse($valor)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Converte o campo Pago para 'sim', 'nao' ou 'nunca'
     */
    private function parsePago($valor)
    {
        if (is_null($valor)) {
            return 'nao';
        }

        $val = strtoupper(trim((string)$valor));

        if ($val === 'SIM')   return 'sim';
        if ($val === 'NUNCA') return 'nunca';

        return 'nao';
    }

    /**
     * Converte para decimal (2 casas decimais)
     * Aceita vírgula ou ponto
     */
    private function parseDecimal($valor)
    {
        if (!$valor) {
            return null;
        }

        // Substitui vírgula por ponto
        $val = str_replace(',', '.', trim((string)$valor));
        $decimal = (float)$val;

        return $decimal > 0 ? $decimal : null;
    }
}