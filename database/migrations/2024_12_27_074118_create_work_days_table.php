<?php

use App\Models\PeriodeCutoff;
use App\Models\Shift;
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
        Schema::create('work_days', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PeriodeCutoff::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Shift::class)->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->boolean('is_off_day')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_days');
    }
};
