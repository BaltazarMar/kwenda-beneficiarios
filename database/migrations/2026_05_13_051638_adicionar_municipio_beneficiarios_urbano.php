<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiarios_urbano', function (Blueprint $table) {
            $table->string('municipio')->nullable()->after('ip1');
        });
    }

    public function down(): void
    {
        Schema::table('beneficiarios_urbano', function (Blueprint $table) {
            $table->dropColumn('municipio');
        });
    }
};