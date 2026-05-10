<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estagiarios', function (Blueprint $table) {
            $table->string('telefone')->nullable()->after('bi');
            $table->string('curso')->nullable()->after('telefone');
            $table->date('data_inicio')->nullable()->after('curso');
            $table->date('data_termino')->nullable()->after('data_inicio');
        });
    }

    public function down(): void
    {
        Schema::table('estagiarios', function (Blueprint $table) {
            $table->dropColumn(['telefone', 'curso', 'data_inicio', 'data_termino']);
        });
    }
};
