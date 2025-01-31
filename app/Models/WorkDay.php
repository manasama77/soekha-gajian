<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $periode_cutoff_id
 * @property int $user_id
 * @property int $shift_id
 * @property string $tanggal
 * @property int $is_off_day
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\PeriodeCutoff $periode_cutoff
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RequestKehadiran> $request_kehadirans
 * @property-read int|null $request_kehadirans_count
 * @property-read \App\Models\Shift $shift
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkDay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkDay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkDay query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkDay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkDay whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkDay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkDay whereIsOffDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkDay wherePeriodeCutoffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkDay whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkDay whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkDay whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkDay whereUserId($value)
 * @mixin \Eloquent
 */
class WorkDay extends Model
{
    protected $fillable = [
        'periode_cutoff_id',
        'user_id',
        'shift_id',
        'tanggal',
        'is_off_day',
    ];

    public function periode_cutoff()
    {
        return $this->belongsTo(PeriodeCutoff::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function request_kehadirans()
    {
        return $this->hasMany(RequestKehadiran::class);
    }
}
