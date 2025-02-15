<?php

namespace App\Http\Controllers;

use App\Enums\StatusDataKehadiran;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Shift;
use App\Models\WorkDay;
use App\Models\Karyawan;
use App\Models\HariLibur;
use Illuminate\Http\Request;
use App\Models\DataKehadiran;
use App\Models\PeriodeCutoff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class DataKehadiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_id = $request->user_id ?? null;
        $bulan   = $request->bulan ?? null;
        $tahun   = $request->tahun ?? null;

        $data_kehadirans = DataKehadiran::with('shifts');

        if (Auth::user()->hasRole('karyawan')) {
            $data_kehadirans->where('user_id', Auth::user()->id);
        }

        if ($request->has('user_id') && $request->user_id != null) {
            $data_kehadirans->where('user_id', $user_id);
        }

        if ($request->has('bulan') && $request->bulan != null) {
            $data_kehadirans->whereMonth('tanggal', $bulan);
        }

        if ($request->has('tahun') && $request->tahun != null) {
            $data_kehadirans->whereYear('tanggal', $tahun);
        }

        $data_kehadirans = $data_kehadirans->orderBy('tanggal', 'desc')
            ->orderBy('clock_in', 'desc')
            ->paginate(10)
            ->withQueryString();

        $users  = User::where('generate_slip_gaji', true)->get();
        $bulans = $this->list_month();
        $tahuns = $this->list_year();

        $data = [
            'data_kehadirans' => $data_kehadirans,
            'users'           => $users,
            'user_id'         => $user_id,
            'bulans'          => $bulans,
            'bulan'           => $bulan,
            'tahuns'          => $tahuns,
            'tahun'           => $tahun,
        ];

        return view('pages.data_kehadiran.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periode_cutoff = PeriodeCutoff::orderBy('is_active', 'desc')->first();
        $users           = User::where('generate_slip_gaji', true)->get();
        $shifts          = Shift::all();

        $current_d = Carbon::now();

        $default_tipe_kehadiran = 'in';
        if (Auth::user()->hasRole('karyawan')) {
            $check_work_day = WorkDay::with('shift')->where('periode_cutoff_id', $periode_cutoff->id)
                ->where('user_id', Auth::user()->id)
                ->whereDate('tanggal', $current_d)
                ->first();

            if (!$check_work_day) {
                return redirect()->back()->withErrors('Kamu tidak memiliki jadwal hari ini...');
            }

            if ($check_work_day->is_off_day == true) {
                return redirect()->back()->withErrors('Hari ini hari libur kamu...');
            }

            $check_data_kehadiran = DataKehadiran::where('user_id', Auth::user()->id)
                ->where('tanggal', $current_d->toDateString())
                ->first();

            if ($check_data_kehadiran) {
                $default_tipe_kehadiran = 'out';
            }
        }

        $data = [
            'periode_cutoff'         => $periode_cutoff,
            'users'                  => $users,
            'shifts'                 => $shifts,
            'default_tipe_kehadiran' => $default_tipe_kehadiran,
        ];

        return view('pages.data_kehadiran.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            if (Auth::user()->hasRole('karyawan')) {
                $request->merge([
                    'user_id'           => Auth::user()->id,
                    'periode_cutoff_id' => PeriodeCutoff::active()->first()->id
                ]);
            }

            $request->validate([
                'user_id'           => ['required', 'exists:users,id'],
                'periode_cutoff_id' => ['required', 'exists:periode_cutoffs,id'],
                'tipe_kehadiran'    => ['required', 'in:in,out'],
                'foto'              => ['nullable', 'image'],
            ]);

            $periode_cutoff = PeriodeCutoff::orderBy('is_active', 'desc')->first();

            $check_work_day = WorkDay::with('shift')->where('periode_cutoff_id', $periode_cutoff->id)
                ->where('user_id', $request->user_id)
                ->where('tanggal', Carbon::now()->toDateString())
                ->first();

            if (!$check_work_day) {
                throw new Exception('Kamu tidak memiliki jadwal hari ini...');
            }

            $work_day_id = $check_work_day->id;
            $shift_id    = $check_work_day->shift_id;
            $is_off_day  = $check_work_day->is_off_day;

            if ($is_off_day == true) {
                throw new Exception('Hari ini hari libur kamu...');
            }

            $shift               = Shift::find($shift_id);
            $shift_start_time    = Carbon::parse($shift->start_time);
            $is_perbantuan_shift = $shift->is_perbantuan_shift;

            $check = DataKehadiran::where('user_id', $request->user_id)
                ->where('tanggal', Carbon::now()->toDateString())
                ->first();

            if ($request->tipe_kehadiran == 'in') {
                if ($check) {
                    $message = 'Presensi kehadiran berhasil disimpan.';
                    return redirect()->route('data-kehadiran.index')->with('success', $message);
                }
            } elseif ($request->tipe_kehadiran == 'out') {
                if (!$check) {
                    throw new Exception('Kamu belum melakukan presensi kehadiran hari ini.');
                }
            }

            $underscore_name = str_replace(' ', '_', strtolower($request->user()->name));
            $path            = $request->file('foto')->store('foto_kehadiran_' . $underscore_name, 'public');

            $image_manager = new ImageManager(new Driver());
            $image_resize = $image_manager->read(public_path('storage/' . $path));
            $image_resize->scale(height: 1000);
            $image_resize->save(public_path('storage/' . $path));

            $dt        = Carbon::now();
            $tanggal   = $dt->toDateString();
            $clock_out = $dt->toTimeString();

            // check menit terlambat
            if ($request->tipe_kehadiran == 'in') {
                $clock_in            = $dt->toTimeString();
                $toleransi_terlambat = config('app.toleransi_terlambat');
                $jam_toleransi       = (clone $shift_start_time)->addMinutes($toleransi_terlambat);
                $jam_terlambat       = 0;
                $menit_terlambat     = 0;
                $counter_terlambat   = 0;

                if ($dt->lt($jam_toleransi)) {
                    $status            = StatusDataKehadiran::Present;
                } elseif ($dt->gt($jam_toleransi)) {
                    $jam_terlambat = $shift_start_time->diffInHours($dt);

                    // hitung berapa kali 30 menit
                    $menit_terlambat   = $shift_start_time->diffInMinutes($dt);
                    $counter_terlambat = ceil($menit_terlambat / 30);
                    $status            = StatusDataKehadiran::Late;
                }

                DataKehadiran::createOrFirst([
                    'user_id'             => $request->user_id,
                    'work_day_id'         => $work_day_id,
                    'periode_cutoff_id'   => $request->periode_cutoff_id,
                    'shift_id'            => $shift_id,
                    'is_perbantuan_shift' => $is_perbantuan_shift,
                    'tanggal'             => $tanggal,
                    'clock_in'            => $clock_in,
                    'clock_out'           => null,
                    'jam_terlambat'       => abs($jam_terlambat),
                    'menit_terlambat'     => abs($menit_terlambat),
                    'counter_terlambat'   => $counter_terlambat,
                    'foto_in'             => $path,
                    'foto_out'            => null,
                    'status'              => $status,
                ]);
            } elseif ($request->tipe_kehadiran == 'out') {
                $old_data = DataKehadiran::where('user_id', $request->user_id)
                    ->where('tanggal', $dt->toDateString())
                    ->first();

                if ($old_data->foto_out != null) {
                    unlink(public_path('storage/' . $old_data->foto_out));
                }

                DataKehadiran::where('user_id', $request->user_id)
                    ->where('tanggal', $dt->toDateString())
                    ->update([
                        'clock_out' => $clock_out,
                        'foto_out'  => $path,
                    ]);
            }

            $message = ($request->tipe_kehadiran == 'in') ? 'Presensi kehadiran berhasil disimpan.' : 'Presensi pulang berhasil disimpan.';
            DB::commit();
            return redirect()->route('data-kehadiran.index')->with('success', $message);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $path = $request->path;
        return '<img src="' . asset('storage/' . $path) . '" />';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataKehadiran $dataKehadiran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataKehadiran $dataKehadiran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataKehadiran $dataKehadiran, Request $request)
    {
        try {
            DB::beginTransaction();

            if (Auth::user()->hasRole('karyawan')) {
                throw new Exception('You are not allowed to delete data kehadiran !');
            }

            $dataKehadiran->delete();

            DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Data kehadiran deleted successfully !');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    protected function list_month()
    {
        $month = [];
        for ($i = 1; $i <= 12; $i++) {
            $month[] = [
                'month_id'   => $i,
                'month_name' => [
                    '1'  => 'Januari',
                    '2'  => 'Februari',
                    '3'  => 'Maret',
                    '4'  => 'April',
                    '5'  => 'Mei',
                    '6'  => 'Juni',
                    '7'  => 'Juli',
                    '8'  => 'Agustus',
                    '9'  => 'September',
                    '10' => 'Oktober',
                    '11' => 'November',
                    '12' => 'Desember'
                ][$i],
            ];
        }

        return $month;
    }

    protected function list_year()
    {
        $year = [];
        for ($i = date('Y'); $i >= date('Y') - 1; $i--) {
            $year[] = [
                'year' => $i
            ];
        }

        return $year;
    }
}
