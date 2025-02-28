<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\TipeIjin;
use App\Models\WorkDay;
use App\Models\DataIjin;
use App\Models\DataLembur;
use App\Models\DataKehadiran;
use App\Models\PeriodeCutoff;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $total_gaji             = 0;
        $menit_lembur           = 0;
        $counter_lembur         = 0;
        $gaji_lembur            = 0;
        $counter_absen          = 0;
        $potongan_absen         = 0;
        $counter_keterlambatan  = 0;
        $potongan_keterlambatan = 0;
        $counter_ijin           = 0;
        $potongan_ijin          = 0;
        $proyeksi               = 0;

        $periode_cutoff = PeriodeCutoff::active()->first();

        if ((Auth::user()->hasRole('karyawan') && !$periode_cutoff) || !$periode_cutoff) {
            if (!$periode_cutoff) {
                return view('dashboard_empty');
            }
        }

        $periode_cutoff_id = $periode_cutoff->id;
        $start_date        = $periode_cutoff->start_date;
        $end_date          = Carbon::parse($periode_cutoff->end_date->toDateString() . ' 23:59:59');

        $users = User::where('generate_slip_gaji', true);
        // $users->where('id', 3);

        if (Auth::user()->hasRole('karyawan')) {
            $users->where('id', Auth::user()->id);
        }
        $users = $users->get();

        foreach ($users as $user) {
            $user_id               = $user->id;
            $tipe_gaji             = $user->tipe_gaji;
            $gaji_bulanan          = $user->gaji_pokok;
            $gaji_harian           = $user->gaji_harian;
            $gaji_perbantuan_shift = $user->gaji_perbantuan_shift;
            $gajinya               = ($tipe_gaji == 'harian') ? $gaji_harian : $gaji_bulanan;

            // hitung hari kerja
            $total_hari_kerja = WorkDay::where('periode_cutoff_id', $periode_cutoff_id)
                ->where('user_id', $user_id)
                ->where('is_off_day', false)
                ->count();

            $total_hari_kerja = ($total_hari_kerja == 0) ? 1 : $total_hari_kerja;
            $total_gaji += ($tipe_gaji == 'harian') ? $gajinya * $total_hari_kerja : $gajinya;
            $gaji_bulananan_per_hari = ($tipe_gaji == 'bulanan') ? $gaji_bulanan / $total_hari_kerja : 0;

            // hitung lembur
            $sum_lembur = DataLembur::approved()
                ->where('user_id', $user_id)
                ->whereDate('overtime_in', '>=', $start_date)
                ->whereDate('overtime_in', '<=', $end_date)
                ->sum('counter_lembur');
            $menit_lembur   += $sum_lembur;
            $counter_lembur += ($sum_lembur / 30);
            $gaji_lembur    += $sum_lembur * config('app.rate_lembur');

            // hitung absen
            $work_days = WorkDay::with('shift')->where('periode_cutoff_id', $periode_cutoff_id)
                ->where('user_id', $user_id)
                ->where('is_off_day', false)
                ->get();
            foreach ($work_days as $work_day) {
                $work_day_id         = $work_day->id;
                $tanggal             = $work_day->tanggal;
                $is_perbantuan_shift = $work_day->shift->is_perbantuan_shift;

                $check_kehadiran = DataKehadiran::where('user_id', $user_id)
                    ->where('work_day_id', $work_day_id)
                    ->where('tanggal', $tanggal)
                    ->first();

                if ($check_kehadiran) {
                    $counter_keterlambatan += $check_kehadiran->counter_terlambat;
                }

                if (!$check_kehadiran) {
                    $check_ijin = DataIjin::with('user')->where('is_approved', true)
                        ->where('from_date', '>=', $start_date)
                        ->where('to_date', '<=', $end_date)
                        ->where('is_approved', true)
                        ->where('tipe_ijin', TipeIjin::Ijin->value)
                        ->where('user_id', $user_id)
                        ->first();

                    if ($check_ijin) {
                        $counter_ijin++;
                        $potongan_ijin += ($tipe_gaji == 'harian') ? $gaji_harian : $gaji_bulananan_per_hari;
                    } else {
                        $counter_absen++;
                        $potongan_absen += ($is_perbantuan_shift) ? $gaji_perbantuan_shift : (($tipe_gaji == 'harian') ? $gaji_harian : $gaji_bulananan_per_hari);
                    }
                }
            }

            // hitung keterlambatan
        }

        $potongan_keterlambatan = $counter_keterlambatan * config('app.rate_terlambat');

        // perhitungan proyeksi pengeluaran atau pendapatan
        $proyeksi = $total_gaji + $gaji_lembur - $potongan_ijin - $potongan_absen - $potongan_keterlambatan;

        $total_gaji_show = number_format($total_gaji, 2, ',', '.');
        $total_gaji_hide = preg_replace('/\d/', '*', $total_gaji_show);

        $lembur_show = number_format($gaji_lembur, 2, ',', '.');
        $lembur_hide = preg_replace('/\d/', '*', $lembur_show);

        $potongan_absen_show = number_format($potongan_absen, 2, ',', '.');
        $potongan_absen_hide = preg_replace('/\d/', '*', $potongan_absen_show);

        $potongan_keterlambatan_show = number_format($potongan_keterlambatan, 2, ',', '.');
        $potongan_keterlambatan_hide = preg_replace('/\d/', '*', $potongan_keterlambatan_show);

        $potongan_ijin_show = number_format($potongan_ijin, 2, ',', '.');
        $potongan_ijin_hide = preg_replace('/\d/', '*', $potongan_ijin_show);

        $proyeksi_show = number_format($proyeksi, 2, ',', '.');
        $proyeksi_hide = preg_replace('/\d/', '*', $proyeksi_show);

        $data = [
            'lembur_show' => $lembur_show,
            'lembur_hide' => $lembur_hide,

            'potongan_keterlambatan_show' => $potongan_keterlambatan_show,
            'potongan_keterlambatan_hide' => $potongan_keterlambatan_hide,

            'potongan_ijin_show' => $potongan_ijin_show,
            'potongan_ijin_hide' => $potongan_ijin_hide,

            'total_gaji_show' => $total_gaji_show,
            'total_gaji_hide' => $total_gaji_hide,

            'potongan_absen_show' => $potongan_absen_show,
            'potongan_absen_hide' => $potongan_absen_hide,

            'proyeksi_show' => $proyeksi_show,
            'proyeksi_hide' => $proyeksi_hide,
        ];

        return view('dashboard', $data);
    }
}
