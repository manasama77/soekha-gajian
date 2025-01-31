<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Enums\TipeGaji;
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
        $jam_lembur             = 0;
        $gaji_lembur            = 0;
        $thp                    = 0;
        $gaji_kehadiran         = 0;
        $potongan_keterlambatan = 0;
        $potongan_ijin          = 0;
        $total_gaji             = 0;
        $potongan_absen         = 0;
        $proyeksi_pengeluaran   = 0;

        $periode_cutoff = PeriodeCutoff::active()->first();

        if ($periode_cutoff) {
            $periode_cutoff_id = $periode_cutoff->id;
            $start_date        = $periode_cutoff->start_date;
            $end_date          = $periode_cutoff->end_date;

            // start hitung total lembur karyawan
            $data_lemburs = DataLembur::approved()
                ->whereDate('overtime_in', '>=', $start_date)
                ->whereDate('overtime_in', '<=', $end_date);
            if (Auth::user()->hasRole('karyawan')) {
                $data_lemburs->where('user_id', Auth::user()->id);
            }
            $data_lemburs = $data_lemburs->get();
            foreach ($data_lemburs as $data_lembur) {
                $overtime_in  = Carbon::parse($data_lembur->overtime_in);
                $overtime_out = Carbon::parse($data_lembur->overtime_out);

                if ($overtime_in->gte($start_date) && $overtime_out->gte($end_date)) {
                    $eod           = Carbon::parse($overtime_in->toDateString() . ' 23:59:59');
                    $jam_lembur += ceil($overtime_in->diffInHours(date: $eod));
                } else {
                    $jam_lembur += ceil($overtime_in->diffInHours(date: $overtime_out));
                }
            }
            $lembur = round($jam_lembur * (config('app.lembur_rate')), 2);
            // end hitung total lembur karyawan

            // start hitung kehadiran karyawan
            $data_kehadirans = DataKehadiran::with('user')->where('periode_cutoff_id', $periode_cutoff_id);
            if (Auth::user()->hasRole('karyawan')) {
                $data_kehadirans->where('user_id', Auth::user()->id);
            }
            $sum_keterlambatan      = $data_kehadirans->sum('jam_terlambat');
            $potongan_keterlambatan = round($sum_keterlambatan * (config('app.potongan_terlambat')), 2);
            $data_kehadirans        = $data_kehadirans->get();
            foreach ($data_kehadirans as $data_kehadiran) {
                $tipe_gaji = $data_kehadiran->user->tipe_gaji;
                $total_hari_kerja = WorkDay::where('user_id', $data_kehadiran->user_id)
                    ->where('periode_cutoff_id', $periode_cutoff_id)
                    ->where('is_off_day', false)
                    ->count();

                if ($tipe_gaji == TipeGaji::Bulanan->value) {
                    $gaji_harian     = $data_kehadiran->user->gaji_pokok / $total_hari_kerja;
                    $gaji_kehadiran += $gaji_harian;
                } elseif ($tipe_gaji == 'harian') {
                    $gaji_kehadiran += $data_kehadiran->user->gaji_harian;
                }
            }
            // end hitung kehadiran karyawan

            // start hitung ijin
            $data_ijins = DataIjin::with('user')->where('is_approved', true)
                ->where('from_date', '>=', $periode_cutoff->start_date->toDateString())
                ->where('to_date', '<=', $periode_cutoff->start_date->toDateString())
                ->where('is_approved', true)
                ->where('tipe_ijin', 'ijin potong gaji');
            if (Auth::user()->hasRole('karyawan')) {
                $data_ijins->where('user_id', Auth::user()->id);
            }
            $data_ijins = $data_ijins->get();
            foreach ($data_ijins as $data_ijin) {
                $tipe_gaji = $data_ijin->user->tipe_gaji;
                $total_hari_kerja = WorkDay::where('user_id', $data_ijin->user_id)
                    ->where('periode_cutoff_id', $periode_cutoff_id)
                    ->where('is_off_day', false)
                    ->count();
                if ($tipe_gaji == TipeGaji::Bulanan->value) {
                    $gaji_harian    = $data_ijin->user->gaji_pokok / $total_hari_kerja;
                    $potongan_ijin += $gaji_harian * $data_ijin->total_hari;
                }
            }
            // end hitung ijin

            $thp = $lembur + $gaji_kehadiran - $potongan_keterlambatan - $potongan_ijin;

            // hitung total gaji start
            if (Auth::user()->hasRole('karyawan')) {
                $user = User::find(Auth::user()->id);
                $tipe_gaji = $user->tipe_gaji;
                if ($tipe_gaji == TipeGaji::Bulanan->value) {
                    $total_gaji = $user->gaji_pokok;
                } else {
                    $total_hari_kerja = WorkDay::where('user_id', Auth::user()->id)
                        ->where('periode_cutoff_id', $periode_cutoff_id)
                        ->where('is_off_day', false)
                        ->count();
                    $total_gaji = $total_hari_kerja * $user->gaji_harian;
                }
            } else {
                $bulanan_users = User::where('generate_slip_gaji', true)
                    ->get();
                foreach ($bulanan_users as $user) {
                    $tipe_gaji = $user->tipe_gaji;
                    if ($tipe_gaji == TipeGaji::Bulanan->value) {
                        $total_gaji += $user->gaji_pokok;
                    } else {
                        $total_hari_kerja = WorkDay::where('user_id', $user->id)
                            ->where('periode_cutoff_id', $periode_cutoff_id)
                            ->where('is_off_day', false)
                            ->count();
                        $total_gaji += $total_hari_kerja * $user->gaji_harian;
                    }
                }
            }
            // hitung total gaji end

            // hitung potongan absen start
            $users = User::where('generate_slip_gaji', true)
                ->get();

            $current_date = Carbon::now();
            foreach ($users as $user) {
                $user_id     = $user->id;
                $tipe_gaji   = $user->tipe_gaji;
                $gaji_pokok  = $user->gaji_pokok;
                $gaji_harian = $user->gaji_harian;

                $work_days = WorkDay::where('user_id', $user_id)
                    ->whereBetween('tanggal', [
                        $periode_cutoff->start_date->toDateString(),
                        $current_date->toDateString()
                    ])
                    ->where('is_off_day', false)
                    ->get();

                foreach ($work_days as $work_day) {
                    $work_day_id = $work_day->id;
                    $check = DataKehadiran::where('user_id', $user_id)
                        ->where('work_day_id', $work_day_id)
                        ->where('periode_cutoff_id', $periode_cutoff_id)
                        ->count();

                    if ($check == 0) {
                        if ($tipe_gaji == TipeGaji::Bulanan->value) {
                            $total_hari_kerja = WorkDay::where('user_id', $user->id)
                                ->where('periode_cutoff_id', $periode_cutoff_id)
                                ->where('is_off_day', false)
                                ->count();

                            $gaji_harian_bulanan = $gaji_pokok / $total_hari_kerja;
                            $potongan_absen += $gaji_harian_bulanan;
                        } else {
                            $potongan_absen += $gaji_harian;
                        }
                    }
                }
            }
            // hitung potongan absen end

            // proyeksi start
            $proyeksi_pengeluaran = $total_gaji + $lembur - $potongan_absen - $potongan_keterlambatan - $potongan_ijin;
            // proyeksi end
        }

        $lembur_show = number_format($lembur, 2, ',', '.');
        $lembur_hide = preg_replace('/\d/', '*', $lembur_show);

        $gaji_kehadiran_show = number_format($gaji_kehadiran, 2, ',', '.');
        $gaji_kehadiran_hide = preg_replace('/\d/', '*', $gaji_kehadiran_show);

        $potongan_keterlambatan_show = number_format($potongan_keterlambatan, 2, ',', '.');
        $potongan_keterlambatan_hide = preg_replace('/\d/', '*', $potongan_keterlambatan_show);

        $potongan_ijin_show = number_format($potongan_ijin, 2, ',', '.');
        $potongan_ijin_hide = preg_replace('/\d/', '*', $potongan_ijin_show);

        $thp_show = number_format($thp, 2, ',', '.');
        $thp_hide = preg_replace('/\d/', '*', $thp_show);

        $total_gaji_show = number_format($total_gaji, 2, ',', '.');
        $total_gaji_hide = preg_replace('/\d/', '*', $total_gaji_show);

        $potongan_absen_show = number_format($potongan_absen, 2, ',', '.');
        $potongan_absen_hide = preg_replace('/\d/', '*', $potongan_absen_show);

        $proyeksi_pengeluaran_show = number_format($proyeksi_pengeluaran, 2, ',', '.');
        $proyeksi_pengeluaran_hide = preg_replace('/\d/', '*', $proyeksi_pengeluaran_show);

        $data = [
            'lembur_show' => $lembur_show,
            'lembur_hide' => $lembur_hide,

            'gaji_kehadiran_show' => $gaji_kehadiran_show,
            'gaji_kehadiran_hide' => $gaji_kehadiran_hide,

            'potongan_keterlambatan_show' => $potongan_keterlambatan_show,
            'potongan_keterlambatan_hide' => $potongan_keterlambatan_hide,

            'potongan_ijin_show' => $potongan_ijin_show,
            'potongan_ijin_hide' => $potongan_ijin_hide,

            'thp_show' => $thp_show,
            'thp_hide' => $thp_hide,

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
