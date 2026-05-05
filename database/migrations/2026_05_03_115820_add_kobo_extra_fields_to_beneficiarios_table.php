<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiarios', function (Blueprint $table) {
            if (!Schema::hasColumn('beneficiarios', 'instituicao')) {
                $table->string('instituicao')->nullable();
            }
            if (!Schema::hasColumn('beneficiarios', 'municipio_instituicao')) {
                $table->string('municipio_instituicao')->nullable();
            }
            if (!Schema::hasColumn('beneficiarios', 'tecnico')) {
                $table->string('tecnico')->nullable();
            }
            if (!Schema::hasColumn('beneficiarios', 'data_submissao')) {
                $table->timestamp('data_submissao')->nullable();
            }
            if (!Schema::hasColumn('beneficiarios', 'origem')) {
                $table->string('origem')->default('manual');
            }
        });
    }

    public function down(): void
    {
        Schema::table('beneficiarios', function (Blueprint $table) {
            $table->dropColumn([
                'instituicao',
                'municipio_instituicao',
                'tecnico',
                'data_submissao',
                'origem',
            ]);
        });
    }
};