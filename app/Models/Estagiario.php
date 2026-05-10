<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estagiario extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_estagiario';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'nome',
        'sexo',
        'bi',
        'telefone',
        'curso',
        'data_nascimento',
        'data_inicio',
        'data_termino',
        'estado',
    ];
}