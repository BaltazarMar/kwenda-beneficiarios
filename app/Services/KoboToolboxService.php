<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KoboToolboxService
{
    protected string $baseUrl;
    protected string $apiToken;
    protected string $assetUid;

    public function __construct()
    {
        $this->baseUrl  = config('kobotoolbox.base_url', 'https://kf.kobotoolbox.org');
        $this->apiToken = config('kobotoolbox.api_token');
        $this->assetUid = config('kobotoolbox.asset_uid');
    }

    /**
     * Retorna todos os dados submetidos no formulário
     */
    public function getSubmissions(): array
    {
        try {
            $response = Http::timeout(60)->withHeaders([
                'Authorization' => "Token {$this->apiToken}",
                'Accept'        => 'application/json',
            ])->get("{$this->baseUrl}/api/v2/assets/{$this->assetUid}/data/", [
                'format' => 'json',
            ]);

            if ($response->successful()) {
                return $response->json('results', []);
            }

            Log::error('KoBoToolbox API error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return [];
        } catch (\Exception $e) {
            Log::error('KoBoToolbox connection error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Formata os dados brutos do KoBoToolbox para o formato do sistema
     */
    public function formatSubmissions(array $submissions): array
    {
        return collect($submissions)->map(function ($item) {
            return [
                'kobo_id'               => $item['_id'] ?? null,
                'nome'                  => $item['Nome_do_benefici_rio'] ?? $item['nome_do_beneficiario'] ?? null,
                'municipio'             => $this->extractSelectOne($item, 'Munic_pio', 'municipio'),
                'bairro'                => $item['Bairro'] ?? $item['bairro'] ?? null,
                'documento'             => $item['documento'] ?? null,
                'data_nascimento'       => $item['Data_de_Nascimento'] ?? $item['data_de_nascimento'] ?? null,
                'genero'                => $this->extractSelectOne($item, 'G_nero', 'genero'),
                'telefone'              => $item['Telefone'] ?? $item['telefone'] ?? null,
                'categoria'             => $this->formatCategoria($this->extractSelectOne($item, 'Categoria', 'categoria')),
                'tecnico'               => $item['Nome_do_t_cnico'] ?? $item['nome_do_tecnico'] ?? null,
                'instituicao'           => $this->extractSelectOne($item, 'IInstituicao_referenciadora', 'instituicao_referenciadora'),
                'municipio_instituicao' => $this->extractSelectOne($item, 'Munic_pio_da_institui_o', 'municipio_da_instituicao'),
                'data_submissao'        => $item['_submission_time'] ?? null,
            ];
        })->toArray();
    }

    /**
     * Extrai valor de campo Select One (tenta múltiplos nomes de campo)
     */
    private function extractSelectOne(array $item, string ...$keys): ?string
    {
        foreach ($keys as $key) {
            if (!empty($item[$key])) {
                return $item[$key];
            }
        }
        return null;
    }

    /**
     * Formata a categoria removendo underscores e corrigindo caracteres especiais
     */
    private function formatCategoria(?string $categoria): ?string
    {
        if (!$categoria) return null;

        $mapa = [
            'idoso'                                    => 'Idoso',
            'pessoa_com_defici_ncia'                   => 'Pessoa com deficiência',
            'pessoa_com_albinismo'                     => 'Pessoa com albinismo',
            'pessoa_com_doen_a_cr_nica'                => 'Pessoa com doença crónica',
            'crian_as_e_jovens_com_necessidades_espec' => 'Criança e jovem com necessidades especiais',
            'idoso_em_situa_o_de_abandono_ou_de_vuner' => 'Idoso em situação de abandono ou vulnerabilidade',
        ];

        return $mapa[$categoria] ?? ucfirst(str_replace('_', ' ', $categoria));
    }

    /**
     * Retorna submissões já formatadas
     */
    public function getFormattedSubmissions(): array
    {
        $raw = $this->getSubmissions();
        return $this->formatSubmissions($raw);
    }

    /**
     * Retorna o total de submissões no formulário
     */
    public function getTotalSubmissions(): int
    {
        try {
            $response = Http::timeout(60)->withHeaders([
                'Authorization' => "Token {$this->apiToken}",
                'Accept'        => 'application/json',
            ])->get("{$this->baseUrl}/api/v2/assets/{$this->assetUid}/data/", [
                'format' => 'json',
            ]);

            return $response->json('count', 0);
        } catch (\Exception $e) {
            return 0;
        }
    }
}