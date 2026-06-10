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
        Schema::table('beneficiarios_urbano', function (Blueprint $table) {
            // Novos campos — adicionados em ordem lógica
            $table->string('social_id')->nullable()->after('identificador');
            $table->string('numero_da_conta')->nullable()->after('social_id');
            $table->string('numero_administrativo')->nullable()->after('numero_da_conta');
            $table->string('card_id')->nullable()->after('numero_administrativo');
            $table->string('telefone')->nullable()->after('card_id');
            $table->string('agencia')->nullable()->after('telefone');
            $table->string('beneficiario')->nullable()->after('agencia');
            $table->string('contacto')->nullable()->after('beneficiario');
            $table->string('profissao')->nullable()->after('contacto');
            $table->string('provincia_residencia')->nullable()->after('profissao');
            $table->string('municipio_residencia')->nullable()->after('provincia_residencia');
            $table->string('comuna')->nullable()->after('municipio_residencia');
            $table->date('data_inscricao')->nullable()->after('observacao');
            $table->boolean('pago')->default(false)->after('data_inscricao');
            $table->decimal('valor1', 10, 2)->nullable()->after('pago');
            $table->date('data1')->nullable()->after('valor1');
            $table->decimal('rece_valor_agregado', 10, 2)->nullable()->after('data1');
            $table->string('nome_valor_agregado')->nullable()->after('rece_valor_agregado');
            $table->string('coordenada_bancaria')->nullable()->after('nome_valor_agregado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beneficiarios_urbano', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });
    }
};