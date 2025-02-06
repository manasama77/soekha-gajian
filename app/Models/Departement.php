<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $karyawan_user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Karyawan> $karyawans
 * @property-read int|null $karyawans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departement onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departement whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departement withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Departement withoutTrashed()
 * @mixin \Eloquent
 */
class Departement extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
