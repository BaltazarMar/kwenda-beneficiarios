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
    Schema::create('beneficiarios', function (Blueprint $table) {
        $table->id();

        $table->string('social_id')->nullable();
        $table->string('nome');
        $table->string('sexo')->nullable();
        $table->date('data_nasc')->nullable();
        $table->string('profissao')->nullable();

        $table->string('provincia');
        $table->string('municipio');
        $table->string('comuna')->nullable();
        $table->string('bairro')->nullable();

        $table->string('contacto')->nullable();
        $table->string('card_id')->nullable();
        $table->string('agente')->nullable();

        // Pagamento geral
        $table->boolean('pago')->default(0);

        // 🔥 RECORRÊNCIAS + DATAS
        $table->integer('rec1')->default(0);
        $table->date('data1')->nullable();

        $table->integer('rec2')->default(0);
        $table->date('data2')->nullable();

        $table->integer('rec3')->default(0);
        $table->date('data3')->nullable();

        $table->integer('rec4')->default(0);
        $table->date('data4')->nullable();

        $table->integer('rec5')->default(0);
        $table->date('data5')->nullable();

        $table->integer('rec6')->default(0);
        $table->date('data6')->nullable();

        $table->text('observacoes')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiarios');
    }
};
