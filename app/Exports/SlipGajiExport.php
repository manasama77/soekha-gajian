<?php

namespace App\Exports;

use App\Models\SlipGaji;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SlipGajiExport implements FromView
{
    protected $periode_cutoff_id;

    public function __construct($periode_cutoff_id)
    {
        $this->periode_cutoff_id = $periode_cutoff_id;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $data = [];

        $slip_gajis = SlipGaji::with([
            'user',
        ])->where('periode_cutoff_id', $this->periode_cutoff_id)->get();

        $gt = 0;
        foreach ($slip_gajis as $slip_gaji) {
            $nama_karyawan = $slip_gaji->user->name;
            $gaji = ($slip_gaji->tipe_gaji === 'harian') ? $slip_gaji->gaji_kehadiran : $slip_gaji->gaji_pokok;
            $lembur = $slip_gaji->gaji_lembur;
            $perbantuan_shift = $slip_gaji->total_gaji_perbantuan_shift;
            $absensi = ($slip_gaji->tipe_gaji === 'harian') ? 0 : $slip_gaji->potongan_tidak_kerja;
            $keterlambatan = $slip_gaji->potongan_terlambat;
            $ijin = ($slip_gaji->tipe_gaji === 'harian') ? 0 : $slip_gaji->potongan_ijin;
            $take_home_pay = $slip_gaji->take_home_pay;

            array_push($data, [
                'nama_karyawan' => $nama_karyawan,
                'gaji' => $gaji,
                'lembur' => $lembur,
                'perbantuan_shift' => $perbantuan_shift,
                'absensi' => $absensi,
                'keterlambatan' => $keterlambatan,
                'ijin' => $ijin,
                'take_home_pay' => $take_home_pay,
            ]);

            $gt += $take_home_pay;
        }

        return view('excel.recap', [
            'recaps' => $data,
            'gt' => $gt,
        ]);
    }
}
