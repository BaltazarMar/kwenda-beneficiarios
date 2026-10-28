<?php

namespace App\Services;

use App\Models\Beneficiario;
use App\Models\BeneficiarioUrbano;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class VerificacaoExcelService
{
    /**
     * Lê o Excel e devolve as linhas já normalizadas.
     * Os cabeçalhos são reconhecidos pelo nome (não pela posição).
     */
    public function lerFicheiro(string $caminho): array
    {
        $folha = IOFactory::load($caminho)->getActiveSheet()->toArray(null, true, false, false);

        if (count($folha) < 2) {
            throw new \RuntimeException('O ficheiro está vazio ou só tem o cabeçalho.');
        }

        $mapa = $this->mapearColunas($folha[0]);

        if (!isset($mapa['nome'])) {
            throw new \RuntimeException('Não encontrei a coluna do nome (ex.: "Nome do Potencial Beneficiário") na primeira linha.');
        }

        $linhas = [];
        foreach ($folha as $i => $row) {
            if ($i === 0) {
                continue;
            }

            $nome = trim((string) ($row[$mapa['nome']] ?? ''));
            if ($nome === '') {
                continue;
            }

            $dataOriginal = isset($mapa['data']) ? ($row[$mapa['data']] ?? null) : null;

            $linhas[] = [
                'linha'           => $i + 1,
                'nome'            => $nome,
                'data_nascimento' => $this->normalizarData($dataOriginal),
                'data_original'   => is_scalar($dataOriginal) ? trim((string) $dataOriginal) : null,
                'sexo'            => $this->texto($row, $mapa, 'sexo'),
                'bairro'          => $this->texto($row, $mapa, 'bairro'),
                'zona'            => $this->texto($row, $mapa, 'zona'),
                'telefone'        => $this->texto($row, $mapa, 'telefone'),
                'categoria'       => $this->texto($row, $mapa, 'categoria'),
            ];
        }

        if (empty($linhas)) {
            throw new \RuntimeException('Não encontrei nenhuma linha com nome preenchido.');
        }

        return $linhas;
    }

    /**
     * Compara cada linha com o Urbano, o Rural e com as linhas anteriores do próprio ficheiro.
     * Carrega as bases uma só vez e compara em memória (rápido para milhares de linhas).
     *
     * Devolve as mesmas linhas com 'status' (novo | possivel | duplicado)
     * e 'encontrado_em' (Urbano | Rural | Excel (linha N) | null).
     */
    public function verificar(array $linhas): array
    {
        $urbanoNomeData = $urbanoNome = $ruralNomeData = $ruralNome = [];

        $urbano = BeneficiarioUrbano::query()->toBase()->select('nome', 'data_nascimento')->get();
        foreach ($urbano as $b) {
            $n = $this->normalizarNome($b->nome);
            if ($n === '') {
                continue;
            }
            $urbanoNome[$n] = true;
            if ($d = $this->normalizarData($b->data_nascimento)) {
                $urbanoNomeData[$n . '|' . $d] = true;
            }
        }

        $rural = Beneficiario::query()->toBase()->select('nome', 'data_nasc')->get();
        foreach ($rural as $b) {
            $n = $this->normalizarNome($b->nome);
            if ($n === '') {
                continue;
            }
            $ruralNome[$n] = true;
            if ($d = $this->normalizarData($b->data_nasc)) {
                $ruralNomeData[$n . '|' . $d] = true;
            }
        }

        $vistosNomeData = [];
        $vistosNome     = [];

        foreach ($linhas as &$l) {
            $n     = $this->normalizarNome($l['nome']);
            $d     = $l['data_nascimento'];
            $chave = $n . '|' . $d;

            $status = 'novo';
            $onde   = null;

            if ($d && isset($urbanoNomeData[$chave])) {
                $status = 'duplicado'; $onde = 'Urbano';
            } elseif ($d && isset($ruralNomeData[$chave])) {
                $status = 'duplicado'; $onde = 'Rural';
            } elseif ($d && isset($vistosNomeData[$chave])) {
                $status = 'duplicado'; $onde = 'Excel (linha ' . $vistosNomeData[$chave] . ')';
            } elseif (isset($urbanoNome[$n])) {
                $status = 'possivel'; $onde = 'Urbano';
            } elseif (isset($ruralNome[$n])) {
                $status = 'possivel'; $onde = 'Rural';
            } elseif (isset($vistosNome[$n])) {
                $status = 'possivel'; $onde = 'Excel (linha ' . $vistosNome[$n] . ')';
            }

            $l['status']        = $status;
            $l['encontrado_em'] = $onde;

            if ($d && !isset($vistosNomeData[$chave])) {
                $vistosNomeData[$chave] = $l['linha'];
            }
            if (!isset($vistosNome[$n])) {
                $vistosNome[$n] = $l['linha'];
            }
        }
        unset($l);

        return $linhas;
    }

    /** minúsculas, sem acentos, sem espaços a mais */
    public function normalizarNome($nome): string
    {
        $nome = mb_strtolower(Str::ascii(trim((string) $nome)));
        return preg_replace('/\s+/', ' ', $nome);
    }

    /** Converte número de série do Excel, dd/mm/aaaa, aaaa-mm-dd... para aaaa-mm-dd */
    public function normalizarData($valor): ?string
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        if ($valor instanceof \DateTimeInterface) {
            return $valor->format('Y-m-d');
        }

        if (is_numeric($valor)) {
            $n = (float) $valor;
            if ($n > 1 && $n < 80000) {
                return ExcelDate::excelToDateTimeObject($n)->format('Y-m-d');
            }
            return null;
        }

        $v = trim((string) $valor);

        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $v, $m)) {
            return $m[1];
        }

        foreach (['j/n/Y', 'j-n-Y', 'j.n.Y', 'Y/m/d'] as $formato) {
            $d    = \DateTime::createFromFormat('!' . $formato, $v);
            $erro = \DateTime::getLastErrors();
            $ok   = $d && (!$erro || ($erro['warning_count'] + $erro['error_count']) === 0);
            if ($ok) {
                return $d->format('Y-m-d');
            }
        }

        return null;
    }

    private function mapearColunas(array $cabecalho): array
    {
        $regras = [
            'nome'      => ['nome'],
            'data'      => ['nasc'],
            'sexo'      => ['sexo', 'genero'],
            'bairro'    => ['bairro'],
            'zona'      => ['zona'],
            'telefone'  => ['telefone', 'contacto', 'contato'],
            'categoria' => ['categoria'],
        ];

        $mapa = [];
        foreach ($cabecalho as $i => $titulo) {
            $t = mb_strtolower(Str::ascii(trim((string) $titulo)));
            foreach ($regras as $campo => $chaves) {
                if (isset($mapa[$campo])) {
                    continue;
                }
                foreach ($chaves as $chave) {
                    if (str_contains($t, $chave)) {
                        $mapa[$campo] = $i;
                        break 2;
                    }
                }
            }
        }

        return $mapa;
    }

    private function texto(array $row, array $mapa, string $campo): ?string
    {
        if (!isset($mapa[$campo])) {
            return null;
        }

        $v = $row[$mapa[$campo]] ?? null;
        if ($v === null || $v === '') {
            return null;
        }

        if (is_int($v) || is_float($v)) {
            $v = number_format($v, 0, '', '');
        }

        return trim((string) $v);
    }
}