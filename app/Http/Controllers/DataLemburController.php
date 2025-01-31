<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Karyawan;
use App\Models\DataLembur;
use Illuminate\Http\Request;
use App\Models\PeriodeCutoff;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class DataLemburController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $karyawan_id   = $request->karyawan_id ?? null;
        $bulan         = $request->bulan ?? null;
        $tahun         = $request->tahun ?? null;
        $lembur_status = $request->lembur_status ?? 'all';

        $data_lemburs = DataLembur::query();

        if (Auth::user()->hasRole('karyawan')) {
            $data_lemburs->where('karyawan_id', Auth::user()->karyawan->id);
        }

        if ($request->has('karyawan_id') && $request->karyawan_id != null) {
            $data_lemburs->where('karyawan_id', $karyawan_id);
        }

        if ($request->has('bulan') && $request->bulan != null) {
            $data_lemburs->whereMonth(column: 'overtime_in', operator: $bulan);
        }

        if ($request->has('tahun') && $request->tahun != null) {
            $data_lemburs->whereYear('overtime_in', $tahun);
        }

        if ($lembur_status == 'pending') {
            $data_lemburs->whereNull('is_approved');
        } elseif ($lembur_status == 'approved') {
            $data_lemburs->where('is_approved', 1);
        } elseif ($lembur_status == 'reject') {
            $data_lemburs->where('is_approved', 0);
        }

        $data_lemburs = $data_lemburs->orderBy('overtime_in', 'desc')->paginate(10)->withQueryString();

        $karyawans = Karyawan::where('is_active', 1)->get();
        $bulans    = $this->list_month();
        $tahuns    = $this->list_year();

        $data = [
            'data_lemburs'  => $data_lemburs,
            'karyawans'     => $karyawans,
            'karyawan_id'   => $karyawan_id,
            'bulans'        => $bulans,
            'bulan'         => $bulan,
            'tahuns'        => $tahuns,
            'tahun'         => $tahun,
            'lembur_status' => $lembur_status,
        ];

        return view('pages.data_lembur.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periode_cutoffs = PeriodeCutoff::active()->latest()->get();
        $karyawans       = Karyawan::query();

        if (Auth::user()->hasRole('karyawan')) {
            $karyawans->where('id', Auth::user()->karyawan->id);
        }

        $karyawans = $karyawans->get();

        $min_date = $periode_cutoffs->first()->lembur_start->format('d-m-Y');
        $max_date = $periode_cutoffs->first()->lembur_end->format('d-m-Y');

        $min_time = '18:00:00';

        $data = [
            'periode_cutoffs' => $periode_cutoffs,
            'karyawans'       => $karyawans,
            'min_date'        => $min_date,
            'max_date'        => $max_date,
            'min_time'        => $min_time,
        ];

        return view('pages.data_lembur.create', $data);
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
                    'karyawan_id' => Auth::user()->karyawan->id,
                ]);
            }

            $request->validate([
                'karyawan_id'       => ['required', 'exists:karyawans,id'],
                'overtime_in_date'  => ['required', 'date'],
                'overtime_in_time'  => ['required', 'date_format:H:i'],
                'overtime_out_date' => ['required', 'date', 'after_or_equal:overtime_in_date'],
                'overtime_out_time' => ['required', 'date_format:H:i'],
            ]);

            $overtime_in_date  = Carbon::createFromFormat('d-m-Y H:i', $request->overtime_in_date . ' ' . $request->overtime_in_time);
            $overtime_out_date = Carbon::createFromFormat('d-m-Y H:i', $request->overtime_out_date . ' ' . $request->overtime_out_time);
            $overtime_in       = Carbon::parse($overtime_in_date);
            $overtime_out      = Carbon::parse($overtime_out_date);

            $check = DataLembur::where(column: 'karyawan_id', operator: $request->karyawan_id)
                ->whereDate('overtime_in', $overtime_in->toDateString())
                ->where(function ($query) {
                    $query->whereNull('is_approved')
                        ->orWhere('is_approved', false);
                })
                ->first();

            if ($check) {
                DB::rollBack();
                return redirect()->route('data-lembur.index')->with('success', 'Data lembur berhasil disimpan.');
            }

            $jam_lembur   = ceil(num: $overtime_in->diffInMinutes(date: $overtime_out) / 60);
            $menit_lembur = ceil($overtime_in->diffInMinutes(date: $overtime_out));

            DataLembur::createOrFirst([
                'karyawan_id'  => $request->karyawan_id,
                'overtime_in'  => $overtime_in->toDateTimeString(),
                'overtime_out' => $overtime_out->toDateTimeString(),
                'jam_lembur'   => $jam_lembur,
                'menit_lembur' => $menit_lembur,
                'is_approved'  => null,
                'approved_by'  => null,
                'approved_at'  => null,
            ]);

            DB::commit();
            // return redirect()->route('data-lembur.index')->with('success', 'Data lembur berhasil disimpan.');
            return redirect()->back()->with('success', 'Data lembur berhasil disimpan.');
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
    public function edit(DataLembur $dataLembur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataLembur $dataLembur)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataLembur $dataLembur)
    {
        try {
            DB::beginTransaction();

            $dataLembur->delete();

            DB::commit();
            return redirect()->route('data-lembur.index')->with('success', 'Data lembur deleted successfully !');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage() . ' ' . $e->getLine())->withInput();
        }
    }

    public function approve_reject(Request $request)
    {
        try {
            $request->validate([
                'id'   => ['required', 'exists:data_lemburs,id'],
                'tipe' => ['required', 'in:approve,reject'],
            ]);

            $data_lembur = DataLembur::findOrFail($request->id);
            $is_approved = false;
            $message     = 'Data lembur ditolak.';

            if ($request->tipe == "approve") {
                $is_approved = true;
                $message     = 'Data lembur berhasil diapprove.';
            }

            $data_lembur->update([
                'is_approved' => $is_approved,
                'approved_by' => Auth::id(),
                'approved_at' => Carbon::now(),
            ]);

            return response()->json(['success' => true, 'message' => $message]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
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
