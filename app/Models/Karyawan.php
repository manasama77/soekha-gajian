<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * 
 *
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan query()
 * @mixin \Eloquent
 */
class Karyawan extends Model
{


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
