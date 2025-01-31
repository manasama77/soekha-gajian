<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DataKehadiran> $data_kehadiran
 * @property-read int|null $data_kehadiran_count
 * @property-read mixed $status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RequestKehadiran> $request_kehadiran
 * @property-read int|null $request_kehadiran_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkDay> $work_days
 * @property-read int|null $work_days_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeCutoff active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeCutoff inactive()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeCutoff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeCutoff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeCutoff query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeCutoff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeCutoff whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeCutoff whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeCutoff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeCutoff whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeCutoff whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeriodeCutoff whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PeriodeCutoff extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function getStatusAttribute()
    {
        $value = $this->is_active;
        return $value ? '✅' : '❌';
    }

    public function data_kehadiran()
    {
        return $this->hasMany(DataKehadiran::class);
    }

    public function request_kehadiran()
    {
        return $this->hasMany(RequestKehadiran::class);
    }

    public function work_days()
    {
        return $this->hasMany(WorkDay::class);
    }
}
