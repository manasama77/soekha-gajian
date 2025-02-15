<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
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
	class DataIjin extends \Eloquent {}
}

namespace App\Models{
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
 * @property string|null $clock_out
 * @property int $jam_terlambat
 * @property int $menit_terlambat
 * @property string|null $foto_in
 * @property string|null $foto_out
 * @property StatusDataKehadiran $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PeriodeCutoff|null $periode_cutoffs
 * @property-read \App\Models\Shift|null $shifts
 * @property-read \App\Models\User $user
 * @property-read \App\Models\WorkDay|null $work_days
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereClockIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereClockOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereFotoIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereFotoOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereJamTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereMenitTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran wherePeriodeCutoffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereShiftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereWorkDayId($value)
 * @mixin \Eloquent
 * @property int $is_perbantuan_shift
 * @property int $counter_terlambat
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereCounterTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataKehadiran whereIsPerbantuanShift($value)
 */
	class DataKehadiran extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
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
 * @mixin \Eloquent
 * @property int $counter_lembur
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DataLembur whereCounterLembur($value)
 */
	class DataLembur extends \Eloquent {}
}

namespace App\Models{
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $user
 * @property-read int|null $user_count
 */
	class Departement extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan query()
 * @mixin \Eloquent
 */
	class Karyawan extends \Eloquent {}
}

namespace App\Models{
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
	class PeriodeCutoff extends \Eloquent {}
}

namespace App\Models{
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
 * @property int $is_perbantuan_shift
 * @property int $counter_terlambat
 * @property string $status
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereCounterTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereIsPerbantuanShift($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RequestKehadiran whereStatus($value)
 */
	class RequestKehadiran extends \Eloquent {}
}

namespace App\Models{
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
 * @property int $is_perbantuan_shift
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shift whereIsPerbantuanShift($value)
 */
	class Shift extends \Eloquent {}
}

namespace App\Models{
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
 * @property int $counter_terlambat
 * @property int $rate_terlambat
 * @property int $counter_lembur
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereCounterLembur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereCounterTerlambat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlipGaji whereRateTerlambat($value)
 */
	class SlipGaji extends \Eloquent {}
}

namespace App\Models{
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
 * @property int $gaji_perbantuan_shift
 * @property-read mixed $gaji_perbantuan_shift_idr
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGajiPerbantuanShift($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
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
	class WorkDay extends \Eloquent {}
}

