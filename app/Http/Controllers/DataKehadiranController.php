<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
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

        $data_kehadirans = DataKehadiran::query();

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

        $data_kehadirans = $data_kehadirans
            ->orderBy('tanggal', 'desc')
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
        $periode_cutoffs = PeriodeCutoff::active()->latest()->get();
        $users           = User::query();

        if (Auth::user()->hasRole('karyawan')) {
            $users->where('id', Auth::user()->id);
        }

        $users = $users->where('generate_slip_gaji', true)->get();

        $data = [
            'periode_cutoffs' => $periode_cutoffs,
            'users'           => $users,
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

            $check_hari_libur = HariLibur::where('tanggal', Carbon::now()->toDateString())->first();

            if ($check_hari_libur) {
                throw new Exception('Hari ini adalah hari libur.');
            }

            $check = DataKehadiran::where('karyawan_id', $request->karyawan_id)
                ->where('tanggal', Carbon::now()->toDateString())
                ->first();

            if ($request->tipe_kehadiran == 'in') {
                if ($check) {
                    return redirect()->route('data-kehadiran.index')->with('success', 'Data kehadiran berhasil disimpan.');
                }
            } elseif ($request->tipe_kehadiran == 'out') {
                if (!$check) {
                    throw new Exception('Data kehadiran clock in belum ada.');
                }
            }

            // $underscore_name = str_replace(' ', '_', strtolower($request->user()->name));
            // $path            = $request->file('foto')->store('foto_kehadiran_' . $underscore_name, 'public');
            // $underscore_name = '#';
            $path            = '#';

            // $image_manager = new ImageManager(new Driver());
            // $image_resize = $image_manager->read(public_path('storage/' . $path));
            // $image_resize->scale(height: 1000);
            // $image_resize->save(public_path('storage/' . $path));

            $dt        = Carbon::now();
            $clock_in  = $dt->toTimeString();
            $clock_out = $dt->toTimeString();
            $jam_masuk = Carbon::parse(config('app.jam_masuk'));

            if ($request->tipe_kehadiran == 'in') {
                $jam_terlambat   = 0;
                $menit_terlambat = 0;

                if ($dt->gt($jam_masuk)) {
                    $jam_terlambat   = (int) ceil($jam_masuk->diffInHours($dt));
                    $menit_terlambat = (int) ceil($jam_masuk->diffInMinutes($dt));
                }

                DataKehadiran::createOrFirst([
                    'karyawan_id'       => $request->karyawan_id,
                    'periode_cutoff_id' => $request->periode_cutoff_id,
                    'tanggal'           => $dt->toDateString(),
                    'clock_in'          => $clock_in,
                    'clock_out'         => null,
                    'jam_terlambat'     => abs($jam_terlambat),
                    'menit_terlambat'   => abs($menit_terlambat),
                    'foto_in'           => $path,
                    'foto_out'          => null,
                ]);
            } else {
                $old_data = DataKehadiran::where('karyawan_id', $request->karyawan_id)
                    ->where('tanggal', $dt->toDateString())
                    ->first();

                if ($old_data->foto_out != null) {
                    unlink(public_path('storage/' . $old_data->foto_out));
                }

                DataKehadiran::where('karyawan_id', $request->karyawan_id)
                    ->where('tanggal', $dt->toDateString())
                    ->update([
                        'clock_out' => $clock_out,
                        'foto_out'  => $path,
                    ]);
            }

            DB::commit();
            return redirect()->route('data-kehadiran.index')->with('success', 'Data kehadiran berhasil disimpan.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
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
