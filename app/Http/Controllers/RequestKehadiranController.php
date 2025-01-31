<?php

namespace App\Http\Controllers;

use App\Models\DataKehadiran;
use Exception;
use App\Models\Karyawan;
use App\Models\HariLibur;
use Illuminate\Http\Request;
use App\Models\PeriodeCutoff;
use Illuminate\Support\Carbon;
use App\Models\RequestKehadiran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RequestKehadiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $karyawan_id = $request->karyawan_id ?? null;
        $bulan       = $request->bulan ?? null;
        $tahun       = $request->tahun ?? null;

        $request_kehadirans = RequestKehadiran::query();

        if (Auth::user()->hasRole('karyawan')) {
            $request_kehadirans->where('karyawan_id', Auth::user()->karyawan->id);
        }

        if ($request->has('karyawan_id') && $request->karyawan_id != null) {
            $request_kehadirans->where('karyawan_id', $karyawan_id);
        }

        if ($request->has('bulan') && $request->bulan != null) {
            $request_kehadirans->whereMonth('tanggal', $bulan);
        }

        if ($request->has('tahun') && $request->tahun != null) {
            $request_kehadirans->whereYear('tanggal', $tahun);
        }

        $request_kehadirans = $request_kehadirans
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $karyawans = Karyawan::where('is_active', 1)->get();
        $bulans    = $this->list_month();
        $tahuns    = $this->list_year();

        $data = [
            'request_kehadirans' => $request_kehadirans,
            'karyawans'          => $karyawans,
            'karyawan_id'        => $karyawan_id,
            'bulans'             => $bulans,
            'bulan'              => $bulan,
            'tahuns'             => $tahuns,
            'tahun'              => $tahun,
        ];

        return view('pages.request_kehadiran.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periode_cutoffs = PeriodeCutoff::active()->latest()->get();

        $karyawans = Karyawan::query();

        if (Auth::user()->hasRole('karyawan')) {
            $karyawans->where('id', Auth::user()->karyawan->id);
        }

        $karyawans = $karyawans->get();

        $min_date = $periode_cutoffs->first()->kehadiran_start->format('d-m-Y');
        $max_date = $periode_cutoffs->first()->kehadiran_end->format('d-m-Y');

        $data = [
            'periode_cutoffs' => $periode_cutoffs,
            'karyawans'       => $karyawans,
            'min_date'        => $min_date,
            'max_date'        => $max_date,
        ];

        return view('pages.request_kehadiran.create', $data);
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
                    'karyawan_id'       => Auth::user()->karyawan->id,
                    'periode_cutoff_id' => PeriodeCutoff::active()->first()->id
                ]);
            }

            $request->validate([
                'karyawan_id'       => ['required', 'exists:karyawans,id'],
                'periode_cutoff_id' => ['required', 'exists:periode_cutoffs,id'],
                'tanggal'           => ['required', 'date'],
                'clock_in'          => ['required', 'date_format:H:i'],
                'clock_out'         => ['required', 'date_format:H:i', 'after_or_equal:clock_in'],
                'alasan'            => ['required'],
            ]);


            $tanggal     = Carbon::createFromFormat('d-m-Y', $request->tanggal);
            $clock_in_x  = $tanggal->toDateString() . ' ' . $request->clock_in;
            $clock_out_x = $tanggal->toDateString() . ' ' . $request->clock_out;
            $clock_in    = Carbon::parse($clock_in_x);
            $clock_out   = Carbon::parse($clock_out_x);

            // cek hari libur
            $check_hari_libur = HariLibur::where('tanggal', $tanggal->toDateString())->count();

            if ($check_hari_libur > 0) {
                throw new Exception(message: 'Tanggal yang dipilih adalah hari libur.');
            }

            $check_kehadiran = DataKehadiran::where(column: 'karyawan_id', operator: $request->karyawan_id)
                ->where('tanggal', $tanggal->toDateString())
                ->first();

            if ($check_kehadiran) {
                throw new Exception('Kamu sudah mengisi data kehadiran pada tanggal ini. Silahkan request admin untuk menghapus data lama kehadiranmu.');
            }

            $check_request = RequestKehadiran::where(column: 'karyawan_id', operator: $request->karyawan_id)
                ->where('tanggal', $tanggal->toDateString())
                ->whereIn('is_approved', values: [null, true])
                ->first();

            if ($check_request) {
                return redirect()->route('request-kehadiran.index')->withErrors('Kamu sudah melakukan request kehadiran pada tanggal ini.');
            }

            $jam_terlambat   = 0;
            $menit_terlambat = 0;
            $jam_masuk       = Carbon::parse($tanggal->toDateString() . ' ' . config('app.jam_masuk'));

            if ($clock_in->gt($jam_masuk)) {
                $jam_terlambat   = (int) ceil($jam_masuk->diffInHours(date: $clock_in));
                $menit_terlambat = (int) ceil($jam_masuk->diffInMinutes($clock_in));
            }

            RequestKehadiran::createOrFirst([
                'karyawan_id'       => $request->karyawan_id,
                'periode_cutoff_id' => $request->periode_cutoff_id,
                'tanggal'           => $tanggal->toDateString(),
                'clock_in'          => $clock_in->toDateTimeString(),
                'clock_out'         => $clock_out->toDateTimeString(),
                'jam_terlambat'     => $jam_terlambat,
                'menit_terlambat'   => $menit_terlambat,
                'alasan'            => $request->alasan,
                'is_approved'       => null,
                'approved_by'       => null,
                'approved_at'       => null,
            ]);

            DB::commit();
            return redirect()->route('request-kehadiran.index')->with('success', 'Request kehadiran berhasil disimpan.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestKehadiran $requestKehadiran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequestKehadiran $requestKehadiran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequestKehadiran $requestKehadiran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestKehadiran $requestKehadiran)
    {
        try {
            $requestKehadiran->delete();
            return redirect()->route('request-kehadiran.index')->with('success', 'Request kehadiran berhasil dihapus.');
        } catch (Exception $e) {
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

    public function approve_reject(Request $request)
    {
        try {
            $request->validate([
                'id'   => ['required', 'exists:request_kehadirans,id'],
                'tipe' => ['required', 'in:approve,reject'],
            ]);

            $request_kehadiran = RequestKehadiran::findOrFail($request->id);
            $is_approved       = false;
            $message           = 'Request kehadiran ditolak.';

            if ($request->tipe == "approve") {
                $is_approved = true;
                $message     = 'Request kehadiran berhasil diapprove.';

                DataKehadiran::createOrFirst([
                    'karyawan_id'       => $request_kehadiran->karyawan_id,
                    'periode_cutoff_id' => $request_kehadiran->periode_cutoff_id,
                    'tanggal'           => $request_kehadiran->tanggal->toDateString(),
                    'clock_in'          => $request_kehadiran->clock_in,
                    'clock_out'         => $request_kehadiran->clock_out,
                    'jam_terlambat'     => abs($request_kehadiran->jam_terlambat),
                    'menit_terlambat'   => abs($request_kehadiran->menit_terlambat),
                    'foto_in'           => null,
                    'foto_out'          => null,
                ]);
            }

            $request_kehadiran->update([
                'is_approved' => $is_approved,
                'approved_by' => Auth::id(),
                'approved_at' => Carbon::now(),
            ]);

            return response()->json(['success' => true, 'message' => $message]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
