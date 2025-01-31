<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * 
 *
 * @property int $id
 * @property int $departement_id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property \Illuminate\Support\Carbon $join_date
 * @property string|null $tipe_gaji
 * @property int $gaji_pokok
 * @property int $gaji_harian
 * @property string $whatsapp
 * @property int $total_cuti
 * @property int $sisa_cuti
 * @property int $generate_slip_gaji
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DataIjin> $data_ijin
 * @property-read int|null $data_ijin_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DataKehadiran> $data_kehadiran
 * @property-read int|null $data_kehadiran_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DataLembur> $data_lembur
 * @property-read int|null $data_lembur_count
 * @property-read \App\Models\Departement $departement
 * @property-read mixed $gaji_harian_idr
 * @property-read mixed $gaji_pokok_idr
 * @property-read mixed $whatsapp_link
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RequestKehadiran> $request_kehadiran
 * @property-read int|null $request_kehadiran_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SlipGaji> $slip_gajis
 * @property-read int|null $slip_gajis_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\WorkDay> $work_days
 * @property-read int|null $work_days_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDepartementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGajiHarian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGajiPokok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGenerateSlipGaji($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereJoinDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereSisaCuti($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTipeGaji($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTotalCuti($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereWhatsapp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'departement_id',
        'name',
        'email',
        'password',
        'join_date',
        'tipe_gaji', // bulanan / harian
        'gaji_pokok',
        'gaji_harian',
        'whatsapp',
        'total_cuti',
        'sisa_cuti',
        'generate_slip_gaji',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'join_date'         => 'date',
        ];
    }

    protected function email()
    {
        return Attribute::make(
            get: fn($value) => strtolower($value),
            set: fn($value) => strtolower($value),
        );
    }

    protected function name()
    {
        return Attribute::make(
            get: fn($value) => strtoupper($value),
            set: fn($value) => strtoupper($value),
        );
    }

    public function getGajiPokokIdrAttribute()
    {
        return 'Rp. ' . number_format($this->gaji_pokok, 0, ',', '.');
    }

    public function getGajiHarianIdrAttribute()
    {
        return 'Rp. ' . number_format($this->gaji_harian, 0, ',', '.');
    }

    public function getWhatsappLinkAttribute()
    {
        $whatsapp = $this->whatsapp;

        $whatsapp = str_replace(['-', ' ', '+'], '', $whatsapp);
        $whatsapp = str_replace('0', '62', $whatsapp);

        return 'https://wa.me/' . $whatsapp;
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }

    public function work_days()
    {
        return $this->hasMany(WorkDay::class);
    }

    public function data_kehadiran()
    {
        return $this->hasMany(DataKehadiran::class);
    }

    public function data_lembur()
    {
        return $this->hasMany(DataLembur::class);
    }

    public function data_ijin()
    {
        return $this->hasMany(DataIjin::class);
    }

    public function request_kehadiran()
    {
        return $this->hasMany(RequestKehadiran::class);
    }

    public function slip_gajis()
    {
        return $this->hasMany(SlipGaji::class);
    }
}
