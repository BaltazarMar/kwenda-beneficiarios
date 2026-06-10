<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficiarioUrbano extends Model
{
    use HasFactory;

    protected $table = 'beneficiarios_urbano';

    protected $fillable = [
        // Campos originais (mantêm-se)
        'identificador',
        'nome',
        'sexo',
        'ip1',
        'data_nascimento',
        'tipo_documento',
        'numero_documento',
        'municipio',
        'bairro',
        'categoria',
        'observacao',

        // Novos campos
        'social_id',
        'numero_da_conta',
        'numero_administrativo',
        'card_id',
        'telefone',
        'agencia',
        'beneficiario',
        'contacto',
        'profissao',
        'provincia_residencia',
        'municipio_residencia',
        'comuna',
        'data_inscricao',
        'pago',
        'valor1',
        'data1',
        'rece_valor_agregado',
        'nome_valor_agregado',
        'coordenada_bancaria',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'data_inscricao' => 'date',
        'data1' => 'date',
        'pago' => 'boolean',
        'valor1' => 'decimal:2',
        'rece_valor_agregado' => 'decimal:2',
    ];
}