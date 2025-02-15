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
        Schema::table('slip_gajis', function (Blueprint $table) {
            $table->integer('gaji_perbantuan_shift')->default(0)->after('gaji_harian');
            $table->integer('total_hari_perbantuan_shift')->default(0)->after('gaji_kehadiran');
            $table->integer('total_gaji_perbantuan_shift')->default(0)->after('total_hari_perbantuan_shift');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slip_gajis', function (Blueprint $table) {
            $table->dropColumn('gaji_perbantuan_shift');
            $table->dropColumn('total_hari_perbantuan_shift');
            $table->dropColumn('total_gaji_perbantuan_shift');
        });
    }
};
