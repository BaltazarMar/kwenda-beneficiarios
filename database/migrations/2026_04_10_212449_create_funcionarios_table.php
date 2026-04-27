<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('funcionarios', function (Blueprint $table) {
        $table->id('id_funcionario');
        $table->string('nome');
        $table->string('sexo', 1);
        $table->string('bi')->unique();
        $table->string('telefone');
        $table->date('data_entrada');

        $table->unsignedBigInteger('id_funcao');

        $table->foreign('id_funcao')
              ->references('id_funcao')
              ->on('funcoes')
              ->onDelete('cascade');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
