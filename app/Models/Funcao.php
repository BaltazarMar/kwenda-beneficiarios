<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Funcionario;

class Funcao extends Model
{
    use HasFactory;

    protected $table = 'funcoes'; // 👈 CORREÇÃO IMPORTANTE

    protected $primaryKey = 'id_funcao';

    protected $fillable = [
        'nome'
    ];

    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class, 'id_funcao');
    }
}