<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Shift;
use App\Models\WorkDay;
use App\Models\Karyawan;
use App\Models\HariLibur;
use Illuminate\Http\Request;
use App\Models\DataKehadiran;
use App\Models\PeriodeCutoff;
use Illuminate\Support\Carbon;
use App\Models\RequestKehadiran;
use App\Enums\StatusDataKehadiran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RequestKehadiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user_id = $request->user_id ?? null;
        $bulan   = $request->bulan ?? null;
        $tahun   = $request->tahun ?? null;

        $request_kehadirans = RequestKehadiran::query();

        if (Auth::user()->hasRole('karyawan')) {
            $request_kehadirans->where('user_id', Auth::user()->id);
        }

        if ($request->has('user_id') && $request->user_id != null) {
            $request_kehadirans->where('user_id', $user_id);
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

        $users  = User::get();
        $bulans = $this->list_month();
        $tahuns = $this->list_year();

        $data = [
            'request_kehadirans' => $request_kehadirans,
            'users'              => $users,
            'user_id'            => $user_id,
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

        $users = User::where('generate_slip_gaji', true);

        if (Auth::user()->hasRole('karyawan')) {
            $users->where('id', Auth::user()->id);
        }

        $users = $users->get();

        $min_date = $periode_cutoffs->first()->start_date->format('d-m-Y');
        $max_date = $periode_cutoffs->first()->end_date->format('d-m-Y');

        $data = [
            'periode_cutoffs' => $periode_cutoffs,
            'users'           => $users,
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
                    'user_id'           => Auth::user()->id,
                    'periode_cutoff_id' => PeriodeCutoff::active()->first()->id
                ]);
            }

            $request->validate([
                'user_id'           => ['required', 'exists:users,id'],
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

            // cek work day
            $current_d = Carbon::parse($clock_in_x);

            $check_work_day = WorkDay::with('shift')->where('periode_cutoff_id', $request->periode_cutoff_id)
                ->where('user_id', $request->user_id)
                ->whereDate('tanggal', $current_d->toDateString())
                ->first();

            if (!$check_work_day) {
                throw new Exception('Kamu tidak memiliki jadwal kerja pada tanggal ini.');
            }

            $work_day_id = $check_work_day->id;
            $shift_id    = $check_work_day->shift_id;
            $is_off_day  = $check_work_day->is_off_day;

            if ($is_off_day == true) {
                throw new Exception('Hari ini hari libur kamu...');
            }

            $check_kehadiran = DataKehadiran::where(column: 'user_id', operator: $request->user_id)
                ->where('tanggal', $clock_in->toDateString())
                ->first();

            if ($check_kehadiran) {
                throw new Exception('Kamu sudah mengisi data kehadiran pada tanggal ini. Silahkan request admin untuk menghapus data lama kehadiranmu jika ada perbaikan.');
            }

            $check_request = RequestKehadiran::where(column: 'user_id', operator: $request->user_id)
                ->whereDate('tanggal', $tanggal->toDateString())
                ->where(function ($query) {
                    $query->whereNull('is_approved')
                        ->orWhere('is_approved', true);
                })
                ->first();

            if ($check_request) {
                throw new Exception('Kamu sudah melakukan request kehadiran pada tanggal ini.');
            }

            $shift            = Shift::find($shift_id);
            $shift_start_time = Carbon::parse($tanggal->toDateString() . ' ' . $shift->start_time);

            $toleransi_terlambat = config('app.toleransi_terlambat');
            $jam_toleransi       = (clone $shift_start_time)->addMinutes($toleransi_terlambat);
            $jam_terlambat       = 0;
            $menit_terlambat     = 0;
            $counter_terlambat   = 0;

            if ($current_d->lt($jam_toleransi)) {;
                $status            = StatusDataKehadiran::Present;
            } elseif ($current_d->gt($jam_toleransi)) {
                $jam_terlambat     = $shift_start_time->diffInHours($current_d);
                $menit_terlambat   = $shift_start_time->diffInMinutes($current_d);
                $counter_terlambat = ceil($menit_terlambat / 30);
                $status            = StatusDataKehadiran::Late;
            }

            RequestKehadiran::createOrFirst([
                'user_id'           => $request->user_id,
                'work_day_id'       => $work_day_id,
                'periode_cutoff_id' => $request->periode_cutoff_id,
                'shift_id'          => $shift_id,
                'tanggal'           => $tanggal->toDateString(),
                'clock_in'          => $clock_in->toDateTimeString(),
                'clock_out'         => $clock_out->toDateTimeString(),
                'jam_terlambat'     => $jam_terlambat,
                'menit_terlambat'   => $menit_terlambat,
                'counter_terlambat' => $counter_terlambat,
                'status'            => $status,
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
                    'user_id'           => $request_kehadiran->user_id,
                    'work_day_id'       => $request_kehadiran->work_day_id,
                    'periode_cutoff_id' => $request_kehadiran->periode_cutoff_id,
                    'shift_id'          => $request_kehadiran->shift_id,
                    'tanggal'           => $request_kehadiran->tanggal->toDateString(),
                    'clock_in'          => $request_kehadiran->clock_in,
                    'clock_out'         => $request_kehadiran->clock_out,
                    'jam_terlambat'     => abs($request_kehadiran->jam_terlambat),
                    'menit_terlambat'   => abs($request_kehadiran->menit_terlambat),
                    'counter_terlambat' => abs($request_kehadiran->counter_terlambat),
                    'foto_in'           => null,
                    'foto_out'          => null,
                    'status'            => $request_kehadiran->status,
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
