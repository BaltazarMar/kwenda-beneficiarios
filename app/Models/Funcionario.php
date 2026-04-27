<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Funcao;

class Funcionario extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_funcionario';

    protected $fillable = [
        'nome',
        'sexo',
        'bi',
        'telefone',
        'data_entrada',
        'id_funcao'
    ];

    public function funcao()
    {
        return $this->belongsTo(Funcao::class, 'id_funcao');
    }
    
}
