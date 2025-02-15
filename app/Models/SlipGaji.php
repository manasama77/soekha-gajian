<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $user_id
 * @property int $periode_cutoff_id
 * @property string $tipe_gaji
 * @property int $gaji_pokok
 * @property string $gaji_harian
 * @property int $total_hari_kerja
 * @property string $gaji_kehadiran
 * @property int $total_cuti
 * @property int $total_sakit
 * @property int $total_hari_tidak_kerja
 * @property string $potongan_tidak_kerja
 * @property int $total_hari_ijin
 * @property string $potongan_ijin
 * @property int $jam_terlambat
 * @property int $menit_terlambat
 * @property string $potongan_terlambat
 * @property int $total_jam_lembur
 * @property int $total_menit_lembur
 * @property string $gaji_lembur
 * @property string $take_home_pay
 * @property int $take_home_pay_rounded
 * @property string $file_pdf
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $gaji_harian_idr
 * @property-read mixed $gaji_kehadiran_idr
 * @property-read mixed $gaji_lembur_idr
 * @property-read mixed $gaji_pokok_idr
 * @property-read mixed $potongan_ijin_idr
 * @property-read mixed $potongan_terlambat_idr
 * @property-read mixed $potongan_tidak_kerja_idr
 * @property-read mixed $take_home_pay_idr
 * @property-read mixed $take_home_pay_rounded_idr
 * @property-read \App\Models\PeriodeCutoff $periode_cutoff
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereFilePdf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereGajiHarian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereGajiKehadiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereGajiLembur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereGajiPokok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereJamTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereMenitTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji wherePeriodeCutoffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji wherePotonganIjin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji wherePotonganTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji wherePotonganTidakKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereTakeHomePay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereTakeHomePayRounded($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereTipeGaji($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereTotalCuti($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereTotalHariIjin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereTotalHariKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereTotalHariTidakKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereTotalJamLembur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereTotalMenitLembur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereTotalSakit($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereUserId($value)
 * @mixin \Eloquent
 */
class SlipGaji extends Model
{
    protected $fillable = [
        'user_id',
        'periode_cutoff_id',
        'tipe_gaji', // harian / bulanan / biweekly
        'gaji_pokok',
        'gaji_harian',
        'gaji_perbantuan_shift',
        'total_hari_kerja',
        'gaji_kehadiran',
        'total_hari_perbantuan_shift',
        'total_gaji_perbantuan_shift',
        'total_cuti',
        'total_sakit',
        'total_hari_tidak_kerja',
        'potongan_tidak_kerja',
        'total_hari_ijin',
        'potongan_ijin',
        'jam_terlambat',
        'menit_terlambat',
        'counter_terlambat',
        'potongan_terlambat',
        'total_jam_lembur',
        'total_menit_lembur',
        'counter_lembur',
        'gaji_lembur',
        'take_home_pay',
        'take_home_pay_rounded',
        'file_pdf',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function periode_cutoff()
    {
        return $this->belongsTo(related: PeriodeCutoff::class);
    }

    public function getGajiPokokIdrAttribute()
    {
        return 'Rp. ' . number_format($this->gaji_pokok, 2, ',', '.');
    }

    public function getGajiHarianIdrAttribute()
    {
        return 'Rp. ' . number_format($this->gaji_harian, 2, ',', '.');
    }

    public function getGajiKehadiranIdrAttribute()
    {
        return 'Rp. ' . number_format($this->gaji_kehadiran, 2, ',', '.');
    }

    public function getPotonganTidakKerjaIdrAttribute()
    {
        return 'Rp. ' . number_format($this->potongan_tidak_kerja, 2, ',', '.');
    }

    public function getPotonganIjinIdrAttribute()
    {
        return 'Rp. ' . number_format($this->potongan_ijin, 2, ',', '.');
    }

    public function getPotonganTerlambatIdrAttribute()
    {
        return 'Rp. ' . number_format($this->potongan_terlambat, 2, ',', '.');
    }

    public function getGajiLemburIdrAttribute()
    {
        return 'Rp. ' . number_format($this->gaji_lembur, 2, ',', '.');
    }

    public function getTakeHomePayIdrAttribute()
    {
        return 'Rp. ' . number_format($this->take_home_pay, 2, ',', '.');
    }

    public function getTakeHomePayRoundedIdrAttribute()
    {
        return 'Rp. ' . number_format($this->take_home_pay_rounded, 2, ',', '.');
    }
}
