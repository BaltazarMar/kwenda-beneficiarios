<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiarios', function (Blueprint $table) {
            $table->index('nome');
            $table->index('social_id');
            $table->index('municipio');
            $table->index('bairro');
            $table->index('pago');
            $table->index('sexo');
        });
    }

    public function down(): void
    {
        Schema::table('beneficiarios', function (Blueprint $table) {
            $table->dropIndex(['nome']);
            $table->dropIndex(['social_id']);
            $table->dropIndex(['municipio']);
            $table->dropIndex(['bairro']);
            $table->dropIndex(['pago']);
            $table->dropIndex(['sexo']);
        });
    }
};