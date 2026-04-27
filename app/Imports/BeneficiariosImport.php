<?php

namespace App\Imports;

use App\Models\Beneficiario;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class BeneficiariosImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading,
    WithBatchInserts,
    WithUpserts,
    SkipsEmptyRows,
    SkipsOnError,
    SkipsOnFailure
{
    use SkipsErrors, SkipsFailures;

    public function model(array $row)
    {
        if (empty($row['socialid'])) {
            return null;
        }

        return new Beneficiario([
            'social_id'   => $row['socialid'],
            'nome'        => $row['nome'] ?? null,
            'sexo'        => $row['sexo'] ?? null,
            'data_nasc'   => $this->formatarData($row['data_nasc'] ?? null),
            'profissao'   => $row['profissao'] ?? null,
            'provincia'   => $row['provincia'] ?? null,
            'municipio'   => $row['municipio'] ?? 'Não informado',
            'comuna'      => $row['comuna'] ?? null,
            'bairro'      => $row['bairro'] ?? null,
            'contacto'    => $row['contacto'] ?? null,
            'card_id'     => $row['cardid'] ?? null,
            'agente'      => $row['agente'] ?? null,
            'pago'        => $this->convertPago($row['pago'] ?? null),
            'rec1'        => $this->convertValor($row['1o'] ?? $row['1º'] ?? null),
            'data1'       => $this->formatarData($row['data1'] ?? null),
            'rec2'        => $this->convertValor($row['2o'] ?? $row['2º'] ?? null),
            'data2'       => $this->formatarData($row['data2'] ?? null),
            'rec3'        => $this->convertValor($row['3o'] ?? $row['3º'] ?? null),
            'data3'       => $this->formatarData($row['data3'] ?? null),
            'rec4'        => $this->convertValor($row['4o'] ?? $row['4º'] ?? null),
            'data4'       => $this->formatarData($row['data4'] ?? null),
            'rec5'        => $this->convertValor($row['5o'] ?? $row['5º'] ?? null),
            'data5'       => $this->formatarData($row['data5'] ?? null),
            'rec6'        => $this->convertValor($row['6o'] ?? $row['6º'] ?? null),
            'data6'       => $this->formatarData($row['data6'] ?? null),
            'observacoes' => $row['observacoes'] ?? null,
        ]);
    }

    // ================= UPSERT =================
    public function uniqueBy()
    {
        return 'social_id';
    }

    // ================= ERROS =================
    public function onError(\Throwable $e)
    {
        \Log::error('Erro na importação: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
    }

    public function onFailure(\Maatwebsite\Excel\Validators\Failure ...$failures)
    {
        foreach ($failures as $failure) {
            \Log::warning('Falha na linha ' . $failure->row() . ': ' . implode(', ', $failure->errors()));
        }
    }

    // ================= CHUNK / BATCH =================
    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }

    // ================= HELPERS =================
    private function formatarData($valor)
    {
        try {
            if (empty($valor)) return null;

            if (is_numeric($valor)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($valor)
                    ->format('Y-m-d');
            }

            return \Carbon\Carbon::parse($valor)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function convertPago($valor)
    {
        if (empty($valor)) return 0;

        $valor = strtolower(trim($valor));

        if ($valor === 'sim')                        return 1;
        if ($valor === 'não' || $valor === 'nao')    return 0;
        if ($valor === 'nunca')                      return 2;

        return 0;
    }

    private function convertValor($valor)
    {
        if (empty($valor)) return 0;

        $valor = str_replace(['.', ',', ' '], '', $valor);

        return is_numeric($valor) ? (int) $valor : 0;
    }
}