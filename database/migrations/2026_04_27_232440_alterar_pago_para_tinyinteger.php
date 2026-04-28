<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Só executa a conversão se for PostgreSQL
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE beneficiarios ALTER COLUMN pago DROP DEFAULT');
            DB::statement('ALTER TABLE beneficiarios ALTER COLUMN pago TYPE smallint USING pago::int::smallint');
            DB::statement('ALTER TABLE beneficiarios ALTER COLUMN pago SET DEFAULT 0');
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE beneficiarios ALTER COLUMN pago DROP DEFAULT');
            DB::statement('ALTER TABLE beneficiarios ALTER COLUMN pago TYPE boolean USING pago::boolean');
            DB::statement('ALTER TABLE beneficiarios ALTER COLUMN pago SET DEFAULT false');
        }
    }
};