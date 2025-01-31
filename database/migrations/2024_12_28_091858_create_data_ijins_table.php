<?php

use App\Enums\TipeIjin;
use App\Models\User;
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
        Schema::create('data_ijins', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->enum('tipe_ijin', [
                TipeIjin::Cuti->value,
                TipeIjin::Sakit->value,
                TipeIjin::Ijin->value
            ]);
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('total_hari');
            $table->longText('keterangan');
            $table->string('lampiran')->nullable();
            $table->boolean('is_approved')->nullable();
            $table->foreignIdFor(User::class, 'approved_by')->nullable()->constrained('users');
            $table->date('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_ijins');
    }
};
