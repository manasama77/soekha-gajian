<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $overtime_in
 * @property \Illuminate\Support\Carbon|null $overtime_out
 * @property int $jam_lembur
 * @property int $menit_lembur
 * @property int|null $is_approved
 * @property \App\Models\User|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur approved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur notApproved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur whereJamLembur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur whereMenitLembur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur whereOvertimeIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur whereOvertimeOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur whereUserId($value)
 *
 * @mixin \Eloquent
 */
class DataLembur extends Model
{
    protected $fillable = [
        'user_id',
        'overtime_in',
        'overtime_out',
        'jam_lembur',
        'menit_lembur',
        'counter_lembur',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'overtime_in' => 'datetime',
        'overtime_out' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function approved_by()
    {
        return $this->belongsTo(User::class, 'approved_by')->withTrashed();
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeNotApproved($query)
    {
        return $query->where('is_approved', false);
    }
}
