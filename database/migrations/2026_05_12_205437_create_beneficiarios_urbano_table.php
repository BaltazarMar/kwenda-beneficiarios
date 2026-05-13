<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beneficiarios_urbano', function (Blueprint $table) {
            $table->id();
            $table->string('identificador')->nullable()->unique();
            $table->string('nome')->nullable();
            $table->string('sexo')->nullable();
            $table->string('ip1')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('tipo_documento')->nullable();
            $table->string('numero_documento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('categoria')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beneficiarios_urbano');
    }
};