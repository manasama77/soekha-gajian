<?php

use App\Enums\TipeGaji;
use App\Models\PeriodeCutoff;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('slip_gajis', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(model: User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(PeriodeCutoff::class)->constrained()->cascadeOnDelete();
            $table->enum('tipe_gaji', [
                TipeGaji::Bulanan->value,
                TipeGaji::Biweekly->value,
                TipeGaji::Harian->value,
            ]);
            $table->integer('gaji_pokok');
            $table->decimal('gaji_harian', 10, 2);
            $table->integer('total_hari_kerja');
            $table->decimal('gaji_kehadiran', 10, 2);
            $table->integer('total_cuti');
            $table->integer('total_sakit');
            $table->integer('total_hari_tidak_kerja');
            $table->decimal('potongan_tidak_kerja', 10, 2);
            $table->integer('total_hari_ijin');
            $table->decimal('potongan_ijin', 10, 2);
            $table->integer('jam_terlambat')->default(0);
            $table->integer('menit_terlambat')->default(0);
            $table->integer('counter_terlambat')->default(0);
            $table->decimal('potongan_terlambat', 10, 2);
            $table->integer('rate_terlambat')->default(0);
            $table->integer('total_jam_lembur')->default(0);
            $table->integer('total_menit_lembur')->default(0);
            $table->integer('counter_lembur')->default(0);
            $table->decimal('gaji_lembur', 15, 2);
            $table->decimal('take_home_pay', 10, 2);
            $table->integer(column: 'take_home_pay_rounded');
            $table->longText('file_pdf');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slip_gajis');
    }
};
