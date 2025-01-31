<?php

namespace App\Models;

use App\Enums\TipeIjin;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property TipeIjin $tipe_ijin
 * @property \Illuminate\Support\Carbon $from_date
 * @property \Illuminate\Support\Carbon $to_date
 * @property int $total_hari
 * @property string $keterangan
 * @property string|null $lampiran
 * @property int|null $is_approved
 * @property \App\Models\User|null $approved_by
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin whereFromDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin whereLampiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin whereTipeIjin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin whereToDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin whereTotalHari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataIjin whereUserId($value)
 * @mixin \Eloquent
 */
class DataIjin extends Model
{
    // tipe ijin = ['cuti', 'sakit dengan surat dokter', 'ijin potong gaji']
    protected $fillable = [
        'user_id',
        'tipe_ijin',
        'from_date',
        'to_date',
        'total_hari',
        'keterangan',
        'lampiran',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tipe_ijin'   => TipeIjin::class,
        'from_date'   => 'date',
        'to_date'     => 'date',
        'approved_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approved_by()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
