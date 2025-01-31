<?php

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
        Schema::create('data_lemburs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->dateTime('overtime_in');
            $table->dateTime('overtime_out')->nullable();
            $table->integer('jam_lembur')->default(0);
            $table->integer('menit_lembur')->default(0);
            $table->boolean('is_approved')->nullable();
            $table->foreignIdFor(User::class, 'approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_lemburs');
    }
};
