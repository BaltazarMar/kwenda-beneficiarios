<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Remove o default boolean
        DB::statement('ALTER TABLE beneficiarios ALTER COLUMN pago DROP DEFAULT');
        
        // 2. Converte de boolean para smallint
        DB::statement('ALTER TABLE beneficiarios ALTER COLUMN pago TYPE smallint USING pago::int::smallint');
        
        // 3. Define novo default como inteiro
        DB::statement('ALTER TABLE beneficiarios ALTER COLUMN pago SET DEFAULT 0');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE beneficiarios ALTER COLUMN pago DROP DEFAULT');
        DB::statement('ALTER TABLE beneficiarios ALTER COLUMN pago TYPE boolean USING pago::boolean');
        DB::statement('ALTER TABLE beneficiarios ALTER COLUMN pago SET DEFAULT false');
    }
};