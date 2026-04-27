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
    Schema::table('beneficiarios', function (Blueprint $table) {
        $table->integer('rec1')->nullable()->change();
        $table->integer('rec2')->nullable()->change();
        $table->integer('rec3')->nullable()->change();
        $table->integer('rec4')->nullable()->change();
        $table->integer('rec5')->nullable()->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
