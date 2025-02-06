<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\TipeGaji;
use App\Enums\TipeIjin;
use App\Models\WorkDay;
use App\Models\DataIjin;
use App\Models\Karyawan;
use Carbon\CarbonPeriod;
use App\Models\HariLibur;
use App\Models\DataKasbon;
use App\Models\DataLembur;
use Illuminate\Http\Request;
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
        $gaji_lembur            = 0;
        $potongan_absen         = 0;
        $potongan_keterlambatan = 0;
        $potongan_ijin          = 0;
        $proyeksi_pengeluaran   = 0;

        $periode_cutoff = PeriodeCutoff::active()->first();

        if (!$periode_cutoff) {
            return view('ga_ada_cutoff');
        }

        $periode_cutoff_id = $periode_cutoff->id;
        $start_date        = $periode_cutoff->start_date;
        $end_date          = Carbon::parse($periode_cutoff->end_date->toDateString() . ' 23:59:59');

        $users = User::where('generate_slip_gaji', true);
        if (Auth::user()->hasRole('karyawan')) {
            $users->where('id', Auth::user()->id);
        }
        $users = $users->get();

        foreach ($users as $user) {
            $user_id      = $user->id;
            $tipe_gaji    = $user->tipe_gaji;
            $gaji_bulanan = $user->gaji_pokok;
            $gaji_harian  = $user->gaji_harian;

            $total_hari_kerja = WorkDay::where('periode_cutoff_id', $periode_cutoff_id)
                ->where('user_id', $user_id)
                ->where('is_off_day', false)
                ->count();

            $sum_lembur = DataLembur::approved()
                ->where('user_id', $user_id)
                ->whereDate('overtime_in', '>=', $start_date)
                ->whereDate('overtime_in', '<=', $end_date)
                ->sum('counter_lembur');

            $menit_lembur += $sum_lembur;

            $data_kehadiran = DataKehadiran::where('user_id', $user_id)
                ->where('periode_cutoff_id', $periode_cutoff_id);

            $count_kehadiran   = $data_kehadiran->count();
            $sum_keterlambatan = $data_kehadiran->sum('counter_terlambat');

            if ($tipe_gaji == 'bulanan') {
                $gaji_harian = ($gaji_bulanan / 2) / $total_hari_kerja;
                $total_gaji += $gaji_harian * $total_hari_kerja;
            } else {
                $total_gaji += $gaji_harian * $count_kehadiran;
            }

            $potongan_keterlambatan += $sum_keterlambatan * config('app.rate_terlambat');

            $total_ijin = DataIjin::with('user')->where('is_approved', true)
                ->where('from_date', '>=', $start_date)
                ->where('to_date', '<=', $end_date)
                ->where('is_approved', true)
                ->where('tipe_ijin', TipeIjin::Ijin->value)
                ->where('user_id', $user->id);

            if ($tipe_gaji == 'bulanan') {
                $potongan_ijin = $total_ijin->sum('total_hari') * $gaji_harian;
            } elseif ($tipe_gaji == 'harian') {
                $potongan_ijin = 0;
            }

            $total_sakit = DataIjin::with('user')->where('is_approved', true)
                ->where('from_date', '>=', $start_date)
                ->where('to_date', '<=', $end_date)
                ->where('is_approved', true)
                ->where('tipe_ijin', TipeIjin::Sakit->value)
                ->where('user_id', $user->id);

            $total_cuti = DataIjin::with('user')->where('is_approved', true)
                ->where('from_date', '>=', $start_date)
                ->where('to_date', '<=', $end_date)
                ->where('is_approved', true)
                ->where('tipe_ijin', TipeIjin::Cuti->value)
                ->where('user_id', $user->id);

            if ($tipe_gaji == 'bulanan') {
                $hari_absen = $total_hari_kerja - $count_kehadiran - $total_ijin->sum('total_hari') - $total_sakit->sum('total_hari') - $total_cuti->sum('total_hari');
                $potongan_absen += $hari_absen * $gaji_harian;
            } elseif ($tipe_gaji == 'harian') {
                $hari_absen      = 0;
                $potongan_absen += 0;
            }
        }

        $gaji_lembur = $menit_lembur * config('app.rate_lembur');
        $lembur_show = number_format($gaji_lembur, 2, ',', '.');
        $lembur_hide = preg_replace('/\d/', '*', $lembur_show);

        $total_gaji_show = number_format($total_gaji, 2, ',', '.');
        $total_gaji_hide = preg_replace('/\d/', '*', $total_gaji_show);

        $potongan_absen_show = number_format($potongan_absen, 2, ',', '.');
        $potongan_absen_hide = preg_replace('/\d/', '*', $potongan_absen_show);

        $potongan_keterlambatan_show = number_format($potongan_keterlambatan, 2, ',', '.');
        $potongan_keterlambatan_hide = preg_replace('/\d/', '*', $potongan_keterlambatan_show);

        $potongan_ijin_show = number_format($potongan_ijin, 2, ',', '.');
        $potongan_ijin_hide = preg_replace('/\d/', '*', $potongan_ijin_show);

        $proyeksi_pengeluaran = $total_gaji + $gaji_lembur - $potongan_absen - $potongan_keterlambatan - $potongan_ijin;

        $proyeksi_pengeluaran_show = number_format($proyeksi_pengeluaran, 2, ',', '.');
        $proyeksi_pengeluaran_hide = preg_replace('/\d/', '*', $proyeksi_pengeluaran_show);

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

            'proyeksi_pengeluaran_show' => $proyeksi_pengeluaran_show,
            'proyeksi_pengeluaran_hide' => $proyeksi_pengeluaran_hide,
        ];

        return view('dashboard', $data);
    }
}
