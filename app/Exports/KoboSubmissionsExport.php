<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class KoboSubmissionsExport
{
    protected array $submissions;

    const COR_AZUL_CLARO = 'FFD6E4F0';
    const COR_CINZA      = 'FFF2F2F2';
    const COR_BRANCO     = 'FFFFFFFF';

    public function __construct(array $submissions)
    {
        // Filtra apenas novos e possíveis (exclui duplicados confirmados)
        $this->submissions = collect($submissions)
            ->where('status', '!=', 'duplicado')
            ->values()
            ->toArray();
    }

    public function download(string $filename): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        // Caminho do modelo — coloca o ficheiro Modelo_kwenda.xlsx em storage/app/
        $modeloPath = storage_path('app/modelo_kwenda.xlsx');

        $spreadsheet = IOFactory::load($modeloPath);
        $ws          = $spreadsheet->getSheetByName('Beneficiários');
        $ws2         = $spreadsheet->getSheetByName('Resumo por Categoria');
        $ws3         = $spreadsheet->getSheetByName('Resumo por Instituição');

        $maxLinhas        = max(count($this->submissions) + 10, 20);
        $ultimaLinhaDados = 4 + $maxLinhas;
        $linhaTotais      = $ultimaLinhaDados + 1;

        // Preencher dados a partir da linha 5
        foreach ($this->submissions as $i => $sub) {
            $excelRow  = 5 + $i;
            $fillColor = ($i % 2 === 0) ? self::COR_CINZA : self::COR_BRANCO;

            // Coluna A — Nº automático
            $ws->setCellValue("A{$excelRow}", "=IF(B{$excelRow}<>\"\",ROW()-4,\"\")");
            $this->styleCell($ws, "A{$excelRow}", $fillColor, false, 'center');

            // Colunas B a J
            $campos = [
                'B' => $sub['nome']            ?? null,
                'C' => $sub['municipio']       ?? null,
                'D' => $sub['bairro']          ?? null,
                'E' => $sub['documento']       ?? null,
                'F' => $sub['data_nascimento'] ?? null,
                'G' => $sub['genero']          ?? null,
                'H' => $sub['telefone']        ?? null,
                'I' => $sub['categoria']       ?? null,
                'J' => $sub['tecnico']         ?? null,
            ];

            foreach ($campos as $col => $val) {
                $ws->setCellValue("{$col}{$excelRow}", $val ?? '');
                $this->styleCell($ws, "{$col}{$excelRow}", $fillColor);
            }

            $ws->getRowDimension($excelRow)->setRowHeight(18);
        }

        // Linhas vazias restantes
        for ($i = count($this->submissions); $i < $maxLinhas; $i++) {
            $excelRow  = 5 + $i;
            $fillColor = ($i % 2 === 0) ? self::COR_CINZA : self::COR_BRANCO;

            $ws->setCellValue("A{$excelRow}", "=IF(B{$excelRow}<>\"\",ROW()-4,\"\")");
            $this->styleCell($ws, "A{$excelRow}", $fillColor, false, 'center');

            foreach (['B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'] as $col) {
                $ws->setCellValue("{$col}{$excelRow}", '');
                $this->styleCell($ws, "{$col}{$excelRow}", $fillColor);
            }

            $ws->getRowDimension($excelRow)->setRowHeight(18);
        }

        // Linha de totais
        $ws->mergeCells("A{$linhaTotais}:H{$linhaTotais}");
        $ws->setCellValue("A{$linhaTotais}", 'TOTAL DE BENEFICIÁRIOS:');
        $ws->setCellValue("I{$linhaTotais}", "=COUNTA(B5:B{$ultimaLinhaDados})");
        $ws->setCellValue("J{$linhaTotais}", 'beneficiários registados');

        foreach (['A', 'I', 'J'] as $col) {
            $ws->getStyle("{$col}{$linhaTotais}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['argb' => 'FF1F4E79'], 'name' => 'Arial', 'size' => 10],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => self::COR_AZUL_CLARO]],
            ]);
        }

        // Actualizar fórmulas Resumo por Categoria
        for ($row = 3; $row <= 7; $row++) {
            $ws2->setCellValue("B{$row}", "=COUNTIF(Beneficiários!I5:I{$ultimaLinhaDados},A{$row})");
            $ws2->setCellValue("C{$row}", "=IF(B8>0,B{$row}/B8,0)");
        }

        // Actualizar fórmulas Resumo por Instituição
        for ($row = 3; $row <= 6; $row++) {
            $ws3->setCellValue("B{$row}", "=COUNTIF(Beneficiários!A5:A{$ultimaLinhaDados},A{$row})");
            $ws3->setCellValue("C{$row}", "=IF(B7>0,B{$row}/B7,0)");
        }

        // Guardar e enviar
        $tempFile = tempnam(sys_get_temp_dir(), 'kwenda_') . '.xlsx';
        $writer   = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    private function styleCell($ws, string $coord, string $fillColor, bool $bold = false, string $align = 'left'): void
    {
        $ws->getStyle($coord)->applyFromArray([
            'font'      => ['name' => 'Arial', 'size' => 10, 'bold' => $bold],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $fillColor]],
            'alignment' => ['horizontal' => $align, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFBFBFBF']],
            ],
        ]);
    }
}