<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeneficiarioUrbano extends Model
{
    use HasFactory;

    protected $table = 'beneficiarios_urbano';

    protected $fillable = [
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
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];
}