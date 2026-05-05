<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('beneficiarios', function (Blueprint $table) {
            $table->string('kobo_id')->nullable()->unique()
                  ->comment('ID único da submissão no KoBoToolbox');
            $table->string('instituicao')->nullable()
                  ->comment('Instituição que referenciou o beneficiário');
            $table->string('municipio_instituicao')->nullable()
                  ->comment('Município da instituição referenciadora');
            $table->string('tecnico')->nullable()
                  ->comment('Nome do técnico que fez o registo');
            $table->timestamp('data_submissao')->nullable()
                  ->comment('Data e hora da submissão no KoBoToolbox');
            $table->string('origem')->default('manual')
                  ->comment('Origem do registo: manual, kobo, excel');
        });
    }

    public function down(): void
    {
        Schema::table('beneficiarios', function (Blueprint $table) {
            $table->dropColumn([
                'kobo_id',
                'instituicao',
                'municipio_instituicao',
                'tecnico',
                'data_submissao',
                'origem',
            ]);
        });
    }
};