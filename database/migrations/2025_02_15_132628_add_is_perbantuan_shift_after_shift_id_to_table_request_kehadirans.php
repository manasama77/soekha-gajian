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
        Schema::table('request_kehadirans', function (Blueprint $table) {
            $table->boolean('is_perbantuan_shift')->default(false)->after('shift_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_kehadirans', function (Blueprint $table) {
            $table->dropColumn('is_perbantuan_shift');
        });
    }
};
