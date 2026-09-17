<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiarios_urbano', function (Blueprint $table) {
            $table->string('pago')->default('nao')->change();
        });
    }

    public function down(): void
    {
        Schema::table('beneficiarios_urbano', function (Blueprint $table) {
            $table->boolean('pago')->default(false)->change();
        });
    }
};