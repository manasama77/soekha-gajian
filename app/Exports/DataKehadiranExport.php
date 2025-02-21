<?php

namespace App\Exports;

use App\Models\User;
use App\Models\WorkDay;
use Carbon\CarbonPeriod;
use App\Models\DataKehadiran;
use App\Models\PeriodeCutoff;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DataKehadiranExport implements FromView
{
    public $user_id;
    public $periode_cutoff_id;

    public function __construct($user_id, $periode_cutoff_id)
    {
        $this->user_id           = $user_id;
        $this->periode_cutoff_id = $periode_cutoff_id;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $data = [];

        $periode_cutoff = PeriodeCutoff::find($this->periode_cutoff_id);
        $start_date     = $periode_cutoff->start_date;
        $end_date       = $periode_cutoff->end_date;

        $carbon_periode = CarbonPeriod::create($start_date, '1 day', $end_date);

        $users   = User::where('generate_slip_gaji', true)->orderBy('name', 'asc');
        if ($this->user_id != null) {
            $users->where('id', $this->user_id);
        }

        $users = $users->get();

        foreach ($users as $user) {
            $user_id = $user->id;
            $nama_karyawan = $user->name;

            foreach ($carbon_periode as $date) {
                $tanggal = $date->format('Y-m-d');

                $work_day = WorkDay::with('shift')->where('tanggal', $tanggal)->where('user_id', $user_id)->first();

                if ($work_day) {
                    $work_day_id         = $work_day->id;
                    $is_off_day          = $work_day->is_off_day;
                    $shift               = strtoupper($work_day->shift->name);
                    $kehadiran           = null;
                    $pulang              = null;
                    $terlambat           = null;
                    $nilai_keterlambatan = null;
                    $is_late             = null;
                    $status              = $is_off_day == 1 ? "OFF DAY" : "ABSEN";

                    if ($is_off_day == 0) {
                        $data_kehadiran = DataKehadiran::where('user_id', $user_id)
                            ->where('work_day_id', $work_day_id)
                            ->where('tanggal', $tanggal)
                            ->first();

                        if ($data_kehadiran) {
                            $kehadiran           = $data_kehadiran->clock_in;
                            $pulang              = $data_kehadiran->clock_out;
                            $terlambat           = $data_kehadiran->menit_terlambat;
                            $nilai_keterlambatan = $data_kehadiran->counter_terlambat;
                            $is_late             = $data_kehadiran->counter_terlambat > 0 ? "TERLAMBAT" : "TIDAK TERLAMBAT";
                            $status              = "MASUK $is_late";
                        }
                    }

                    $data[] = [
                        'tanggal'             => $tanggal,
                        'nama_karyawan'       => $nama_karyawan,
                        'shift'               => $shift,
                        'kehadiran'           => $kehadiran,
                        'pulang'              => $pulang,
                        'terlambat'           => $terlambat,
                        'nilai_keterlambatan' => $nilai_keterlambatan,
                        'is_late'             => $is_late,
                        'status'              => $status,
                    ];
                }
            }
        }

        return view('excel.rekap_data_kehadiran', [
            'data_kehadirans' => $data,
        ]);
    }
}
