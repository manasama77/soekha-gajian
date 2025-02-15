<?php

namespace App\Http\Controllers;

use App\Enums\StatusDataKehadiran;
use Exception;
use App\Models\User;
use App\Models\DataIjin;
use App\Models\SlipGaji;
use App\Models\DataLembur;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\DataKehadiran;
use App\Models\PeriodeCutoff;
use Illuminate\Support\Carbon;
use App\Exports\SlipGajiExport;
use App\Models\WorkDay;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PeriodeCutoffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $periode_cutoffs = PeriodeCutoff::query();

        if ($request->has('search') && !is_null($request->search)) {
            $periode_cutoffs->where('nama', 'like', "%$request->search%");
        }

        $periode_cutoffs = $periode_cutoffs
            ->orderBy('start_date', 'desc')
            ->paginate(10)->withQueryString();

        $data = [
            'periode_cutoffs' => $periode_cutoffs,
            'search'          => $request->search
        ];
        return view('pages.periode_cutoff.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.periode_cutoff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
            'is_active'  => ['boolean'],
        ]);

        if ($request->is_active) {
            PeriodeCutoff::query()->update(['is_active' => false]);
        }

        PeriodeCutoff::create($request->all());
        return redirect()->route('setup.periode-cutoff.index')->with('success', 'Periode Cutoff berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(PeriodeCutoff $periodeCutoff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PeriodeCutoff $periodeCutoff)
    {
        $data = [
            'periode_cutoff' => $periodeCutoff,
        ];

        return view('pages.periode_cutoff.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PeriodeCutoff $periodeCutoff)
    {
        $request->validate([
            'start_date' => ['required', 'date'],
            'end_date'   => ['required', 'date', 'after:start_date'],
            'is_active'  => ['boolean'],
        ]);

        if ($request->is_active) {
            PeriodeCutoff::query()->update(['is_active' => false]);
        }

        $periodeCutoff->update($request->all());
        return redirect()->route('setup.periode-cutoff.index')->with('success', 'Periode Cutoff berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PeriodeCutoff $periodeCutoff)
    {
        $periodeCutoff->delete();
        return redirect()->route('setup.periode-cutoff.index')->with('success', 'Periode Cutoff berhasil dihapus');
    }

    public function generate_slip_gaji(Request $request)
    {
        try {
            DB::beginTransaction();

            $periode_cutoff_id = (int) $request->id;
            $periode_cutoffs   = PeriodeCutoff::where('is_active', true)->find($periode_cutoff_id);

            if (!$periode_cutoffs) {
                throw new Exception('Periode cutoff tidak ditemukan atau tidak aktif!');
            }

            $start_date = $periode_cutoffs->start_date;
            $end_date   = $periode_cutoffs->end_date;

            $users = User::with('departement')
                ->where('generate_slip_gaji', true)
                ->get();

            foreach ($users as $user) {
                $user_id               = $user->id;
                $name                  = $user->name;
                $departement           = $user->departement->name;
                $tipe_gaji             = $user->tipe_gaji;
                $gaji_pokok            = (float) $user->gaji_pokok;
                $gaji_biweekly         = (float) $gaji_pokok / 2;
                $gaji_harian           = $user->gaji_harian;
                $gaji_perbantuan_shift = $user->gaji_perbantuan_shift;
                $arr_kehadiran         = [];
                $arr_lembur            = [];

                $hari_kerjas = WorkDay::where('periode_cutoff_id', $periode_cutoff_id)
                    ->where('user_id', $user_id)
                    ->where('is_off_day', false);

                if ($tipe_gaji === 'bulanan') {
                    $gaji_harian = round($gaji_biweekly / $hari_kerjas->count(), 2);
                }

                $total_cuti      = 0;
                $total_sakit     = 0;
                $total_hari_ijin = 0;
                $potongan_ijin   = 0;
                $gaji_kehadiran  = 0;

                $data_kehadiran = DataKehadiran::with('shifts')->where('user_id', $user_id)
                    ->where('periode_cutoff_id', $periode_cutoff_id)
                    ->where('is_perbantuan_shift', 0)
                    ->whereBetween('tanggal', [$start_date, $end_date])
                    ->whereNotNull('clock_in');

                $data_kehadiran_terlambat = DataKehadiran::with('shifts')->where('user_id', $user_id)
                    ->where('periode_cutoff_id', $periode_cutoff_id)
                    ->whereBetween('tanggal', [$start_date, $end_date])
                    ->whereNotNull('clock_in');

                $data_perbantuan_shift = DataKehadiran::with('shifts')->where('user_id', $user_id)
                    ->where('periode_cutoff_id', $periode_cutoff_id)
                    ->where('is_perbantuan_shift', 1)
                    ->whereBetween('tanggal', [$start_date, $end_date])
                    ->whereNotNull('clock_in');

                $data_cuti = DataIjin::where('user_id', $user_id)
                    ->where('from_date', '>=', $start_date)
                    ->where('to_date', '<=', $end_date)
                    ->where('is_approved', true)
                    ->where('tipe_ijin', 'cuti');

                $data_sakit = DataIjin::where('user_id', $user_id)
                    ->where('from_date', '>=', $start_date)
                    ->where('to_date', '<=', $end_date)
                    ->where('is_approved', true)
                    ->where('tipe_ijin', 'sakit dengan surat dokter');

                // adam
                $total_hari_kerja            = (int) $data_kehadiran->count();
                $total_hari_perbantuan_shift = (int) $data_perbantuan_shift->count();
                $total_gaji_perbantuan_shift = (int) $total_hari_perbantuan_shift * $gaji_perbantuan_shift;
                $total_cuti                  = (int) $data_cuti->sum('total_hari');
                $total_sakit                 = (int) $data_sakit->sum('total_hari');
                $jam_terlambat               = (int) $data_kehadiran_terlambat->sum('jam_terlambat');
                $menit_terlambat             = (int) $data_kehadiran_terlambat->sum('menit_terlambat');
                $sum_counter_terlambat       = (int) $data_kehadiran_terlambat->sum('counter_terlambat');
                $potongan_terlambat          = round(config('app.rate_terlambat') * $sum_counter_terlambat, 2);

                foreach ($hari_kerjas->get() as $hs) {
                    $tanggal = Carbon::parse($hs->tanggal);

                    $check_ijin = DataIjin::where('user_id', $user_id)
                        ->where('from_date', '<=', $tanggal->toDateString())
                        ->where('to_date', '>=', $tanggal->toDateString())
                        ->where('is_approved', true)
                        ->first();

                    if ($check_ijin) {
                        array_push($arr_kehadiran, [
                            'tanggal'           => $tanggal->toDateString(),
                            'shift'             => null,
                            'jam_masuk'         => null,
                            'jam_pulang'        => null,
                            'menit_terlambat'   => null,
                            'counter_terlambat' => null,
                            'status'            => $check_ijin->tipe_ijin->value,
                        ]);
                        continue;
                    }

                    $check_kehadiran = DataKehadiran::with('shifts')->where('user_id', $user_id)
                        ->where('periode_cutoff_id', $periode_cutoff_id)
                        ->where('tanggal', $tanggal->toDateString())
                        ->whereNotNull('clock_in')
                        ->first();

                    if (!$check_kehadiran) {
                        array_push($arr_kehadiran, [
                            'tanggal'           => $tanggal->toDateString(),
                            'shift'             => null,
                            'jam_masuk'         => null,
                            'jam_pulang'        => null,
                            'menit_terlambat'   => null,
                            'counter_terlambat' => null,
                            'status'            => StatusDataKehadiran::Absent->value,
                        ]);
                        continue;
                    }

                    if ($check_kehadiran->shift_id != 1) {
                        array_push($arr_kehadiran, [
                            'tanggal'           => $tanggal->toDateString(),
                            'shift'             => $check_kehadiran->shifts->name,
                            'jam_masuk'         => $check_kehadiran->clock_in,
                            'jam_pulang'        => $check_kehadiran->clock_out,
                            'menit_terlambat'   => $check_kehadiran->menit_terlambat,
                            'counter_terlambat' => $check_kehadiran->counter_terlambat,
                            'status'            => $check_kehadiran->status->value
                        ]);
                        continue;
                    } else {
                        array_push($arr_kehadiran, [
                            'tanggal'           => $tanggal->toDateString(),
                            'shift'             => $check_kehadiran->shifts->name,
                            'jam_masuk'         => null,
                            'jam_pulang'        => null,
                            'menit_terlambat'   => null,
                            'counter_terlambat' => null,
                            'status'            => 'libur',
                        ]);
                        continue;
                    }
                }

                $data_lemburs = DataLembur::where('user_id', $user_id)
                    ->whereDate('overtime_in', '>=', $start_date)
                    ->whereDate('overtime_in', '<=', $end_date)
                    ->where('is_approved', true);

                $total_jam_lembur   = $data_lemburs->sum('jam_lembur');
                $total_menit_lembur = $data_lemburs->sum('menit_lembur');
                $sum_counter_lembur = $data_lemburs->sum('counter_lembur');
                $gaji_lembur        = round($sum_counter_lembur * config('app.rate_lembur'), 2);

                foreach ($data_lemburs->get() as $dk) {
                    array_push($arr_lembur, [
                        'tanggal'        => $dk->overtime_in->toDateString(),
                        'jam_masuk'      => $dk->overtime_in->toDateTimeString(),
                        'jam_pulang'     => $dk->overtime_out->toDateTimeString(),
                        'menit_lembur'   => $dk->menit_lembur,
                        'counter_lembur' => $dk->counter_lembur,
                    ]);
                }

                $data_ijin = DataIjin::where('user_id', $user_id)
                    ->where('from_date', '>=', $start_date)
                    ->where('to_date', '<=', $end_date)
                    ->where('is_approved', true)
                    ->where('tipe_ijin', 'ijin potong gaji');

                $total_hari_ijin = (int) $data_ijin->sum('total_hari');
                $potongan_ijin   = round($gaji_harian * $total_hari_ijin, 2);

                // dd(
                //     [
                //         'hari_kerja'       => $hari_kerjas->count(),
                //         'total_hari_kerja' => $total_hari_kerja,
                //         'total_cuti'       => $total_cuti,
                //         'total_sakit'      => $total_sakit,
                //         'total_hari_ijin'  => $total_hari_ijin,
                //     ]
                // );

                $total_hari_tidak_kerja = $hari_kerjas->count() - $total_hari_kerja - $total_cuti - $total_sakit - $total_hari_ijin;
                $potongan_tidak_kerja   = round($gaji_harian * $total_hari_tidak_kerja, 2);

                $take_home_pay = round($gaji_biweekly + $gaji_lembur - $potongan_tidak_kerja - $potongan_terlambat - $potongan_ijin + $total_gaji_perbantuan_shift, 2);

                $gaji_kehadiran = round($gaji_harian * $total_hari_kerja, 2);
                if ($tipe_gaji === 'harian') {
                    $take_home_pay  = round($gaji_kehadiran + $gaji_lembur - $potongan_terlambat + $total_gaji_perbantuan_shift, 2);
                }

                $take_home_pay_rounded = $take_home_pay;
                $hundreds              = round($take_home_pay, -2);
                $thousands             = round($take_home_pay, -3);

                if (abs($take_home_pay - $hundreds) < abs($take_home_pay - $thousands)) {
                    $take_home_pay_rounded = $hundreds;
                } else {
                    $take_home_pay_rounded = $thousands;
                }

                $nama_file = Str::slug($start_date->toDateString() . '-' . $end_date->toDateString() . '-' . $name . '-' . Carbon::now()->format('Y-m-d')) . ".pdf";

                $data_slip_gaji = [
                    [
                        'user_id'           => $user_id,
                        'periode_cutoff_id' => $periode_cutoff_id,
                    ],
                    [
                        'tipe_gaji'                   => $tipe_gaji,
                        'gaji_pokok'                  => $gaji_biweekly,
                        'gaji_harian'                 => $gaji_harian,
                        'gaji_perbantuan_shift'       => $gaji_perbantuan_shift,
                        'total_hari_kerja'            => $total_hari_kerja,
                        'gaji_kehadiran'              => $gaji_kehadiran,
                        'total_hari_perbantuan_shift' => $total_hari_perbantuan_shift,
                        'total_gaji_perbantuan_shift' => $total_gaji_perbantuan_shift,
                        'total_cuti'                  => $total_cuti,
                        'total_sakit'                 => $total_sakit,
                        'total_hari_tidak_kerja'      => $total_hari_tidak_kerja,
                        'potongan_tidak_kerja'        => $potongan_tidak_kerja,
                        'total_hari_ijin'             => $total_hari_ijin,
                        'potongan_ijin'               => $potongan_ijin,
                        'jam_terlambat'               => $jam_terlambat,
                        'menit_terlambat'             => $menit_terlambat,
                        'counter_terlambat'           => $sum_counter_terlambat,
                        'potongan_terlambat'          => $potongan_terlambat,
                        'total_jam_lembur'            => $total_jam_lembur,
                        'total_menit_lembur'          => $total_menit_lembur,
                        'counter_lembur'              => $sum_counter_lembur,
                        'gaji_lembur'                 => $gaji_lembur,
                        'take_home_pay'               => $take_home_pay,
                        'take_home_pay_rounded'       => $take_home_pay_rounded,
                        'file_pdf'                    => $nama_file,
                    ]
                ];

                // dd($data_slip_gaji);

                $slip_gaji    = SlipGaji::updateOrCreate($data_slip_gaji[0], $data_slip_gaji[1]);
                $slip_gaji_id = $slip_gaji->id;

                $slip = SlipGaji::with([
                    'user',
                    'user.departement',
                    'periode_cutoff',
                ])->find($slip_gaji_id);

                $title = "Slip Gaji $name - $departement - " . $start_date->translatedFormat('d M y') . " s/d " . $end_date->translatedFormat('d M y');
                $pdf  = Pdf::loadView('pdf.slip-gaji', [
                    'title'         => $title,
                    'data'          => $slip,
                    'hari_kerja'    => $hari_kerjas->count(),
                    'arr_kehadiran' => $arr_kehadiran,
                    'arr_lembur'    => $arr_lembur,
                ])->setPaper('a4', 'portrait');
                // return $pdf->stream();
                $pdf->save(public_path('storage/slip_gaji/' . $nama_file));
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Slip gaji berhasil digenerate'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Slip gaji gagal digenerate',
                'error'   => $e->getMessage(),
                'line'    => $e->getLine(),
            ]);
        }
    }

    public function excel($periode_cutoff_id)
    {
        $periode_cutoff = PeriodeCutoff::find($periode_cutoff_id);
        if (!$periode_cutoff) {
            return redirect()->route('setup.periode-cutoff.index')->with('error', 'Periode Cutoff tidak ditemukan');
        }

        $start_date = $periode_cutoff->start_date->format('d M Y');
        $end_date   = $periode_cutoff->end_date->format('d M Y');
        $file_name  = $start_date . ' - ' . $end_date . " Rekap Gaji Soekha Coffee";
        return Excel::download(new SlipGajiExport($periode_cutoff_id), $file_name . '.xlsx');
    }
}
