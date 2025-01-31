<?php

namespace App\Http\Controllers;

use App\Models\DataLembur;
use Illuminate\Http\Request;
use App\Models\DataKehadiran;
use Illuminate\Support\Carbon;

class TestController extends Controller
{
    public function index()
    {
        $arr_work_day = [];
        $startDate    = Carbon::parse('2024-12-01');
        $endDate      = Carbon::parse('2024-12-31');

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            if (!$date->isSunday()) {
                $arr_work_day[] = $date->format('Y-m-d');
            }
        }

        dd($arr_work_day);
    }

    public function repair_keterlambatan()
    {
        $data_kehadirans = DataKehadiran::get();

        foreach ($data_kehadirans as $data_kehadiran) {
            $tanggal   = $data_kehadiran->tanggal;
            $clock_in  = Carbon::parse($tanggal->toDateString() . ' ' . $data_kehadiran->clock_in);
            $jam_masuk = Carbon::parse($data_kehadiran->tanggal->toDateString() . ' ' . config('app.jam_masuk'));

            $menit_terlambat = 0;

            if ($clock_in->gt($jam_masuk)) {
                $menit_terlambat = (int) ceil($jam_masuk->diffInMinutes($clock_in));

                DataKehadiran::where('id', $data_kehadiran->id)
                    ->update([
                        'menit_terlambat' => abs($menit_terlambat),
                    ]);
            }
        }
    }

    public function repair_lembur()
    {
        $data_lemburs = DataLembur::get();

        foreach ($data_lemburs as $data_lembur) {
            $overtime_in  = Carbon::parse($data_lembur->overtime_in);
            $overtime_out = Carbon::parse($data_lembur->overtime_out);
            $menit_lembur = (int) ceil($overtime_in->diffInMinutes($overtime_out));

            DataLembur::where('id', $data_lembur->id)
                ->update([
                    'menit_lembur' => abs($menit_lembur),
                ]);
        }
    }

    public function test()
    {
        return redirect()->route('data-lembur.index')->with('success', 'Data lembur berhasil disimpan.');
    }
}
