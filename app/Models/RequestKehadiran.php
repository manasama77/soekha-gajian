<?php

namespace App\Models;

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
 * @property string $clock_out
 * @property int $jam_terlambat
 * @property int $menit_terlambat
 * @property string $alasan
 * @property bool|null $is_approved
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PeriodeCutoff $periode_cutoff
 * @property-read \App\Models\Shift $shift
 * @property-read \App\Models\User $user
 * @property-read \App\Models\WorkDay $work_day
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereAlasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereClockIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereClockOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereJamTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereMenitTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran wherePeriodeCutoffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereWorkDayId($value)
 * @mixin \Eloquent
 */
class RequestKehadiran extends Model
{
    protected $fillable = [
        'user_id',
        'work_day_id',
        'periode_cutoff_id',
        'shift_id',
        'tanggal',
        'clock_in',
        'clock_out',
        'jam_terlambat',
        'menit_terlambat',
        'count_terlambat',
        'status',
        'alasan',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal'         => 'date',
        'jam_terlambat'   => 'integer',
        'menit_terlambat' => 'integer',
        'is_approved'     => 'boolean',
        'approved_at'     => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function work_day()
    {
        return $this->belongsTo(WorkDay::class);
    }

    public function periode_cutoff()
    {
        return $this->belongsTo(PeriodeCutoff::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
