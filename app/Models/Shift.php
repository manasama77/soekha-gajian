<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $start_time
 * @property string $end_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DataKehadiran> $dataKehadirans
 * @property-read int|null $data_kehadirans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RequestKehadiran> $requestKehadirans
 * @property-read int|null $request_kehadirans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkDay> $workDays
 * @property-read int|null $work_days_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Shift extends Model
{
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'is_perbantuan_shift',
    ];

    public function workDays()
    {
        return $this->hasMany(WorkDay::class);
    }

    public function dataKehadirans()
    {
        return $this->hasMany(DataKehadiran::class);
    }

    public function requestKehadirans()
    {
        return $this->hasMany(RequestKehadiran::class);
    }
}
