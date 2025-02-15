<?php

namespace App\Models;

use App\Enums\StatusDataKehadiran;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $user_id
 * @property int $work_day_id
 * @property int $periode_cutoff_id
 * @property int $shift_id
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string $clock_in
 * @property string|null $clock_out
 * @property int $jam_terlambat
 * @property int $menit_terlambat
 * @property string|null $foto_in
 * @property string|null $foto_out
 * @property StatusDataKehadiran $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PeriodeCutoff|null $periode_cutoffs
 * @property-read \App\Models\Shift|null $shifts
 * @property-read \App\Models\User $user
 * @property-read \App\Models\WorkDay|null $work_days
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereClockIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereClockOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereFotoIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereFotoOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereJamTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereMenitTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran wherePeriodeCutoffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereWorkDayId($value)
 * @mixin \Eloquent
 */
class DataKehadiran extends Model
{
    protected $fillable = [
        'user_id',
        'work_day_id',
        'periode_cutoff_id',
        'shift_id',
        'is_perbantuan_shift',
        'tanggal',
        'clock_in',
        'clock_out',
        'jam_terlambat',
        'menit_terlambat',
        'counter_terlambat',
        'foto_in',
        'foto_out',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status'  => StatusDataKehadiran::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function work_days()
    {
        return $this->belongsTo(WorkDay::class);
    }

    public function periode_cutoffs()
    {
        return $this->belongsTo(PeriodeCutoff::class);
    }

    public function shifts()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
}
