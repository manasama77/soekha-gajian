<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\WorkDay;
use App\Models\DataIjin;
use App\Models\Karyawan;
use App\Models\HariLibur;
use Illuminate\Http\Request;
use App\Models\PeriodeCutoff;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DataIjinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $data_ijins = DataIjin::query();

        if (Auth::user()->hasRole('karyawan')) {
            $data_ijins->where('user_id', Auth::user()->id);
        }

        $data_ijins = $data_ijins->orderBy('from_date', 'desc')->paginate(10)->withQueryString();

        $data = [
            'data_ijins' => $data_ijins,
        ];

        return view('pages.data_ijin.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::query();

        if (Auth::user()->hasRole('karyawan')) {
            $users->where('id', Auth::user()->id);
        }

        $users = $users->get();

        $periode_cutoff = PeriodeCutoff::active()->first();

        $min_date = $periode_cutoff->start_date->toDateString();
        $max_date = $periode_cutoff->end_date->toDateString();

        $data = [
            'users'    => $users,
            'min_date' => $min_date,
            'max_date' => $max_date,
        ];

        return view('pages.data_ijin.create', $data);
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
                    'user_id' => Auth::user()->id,
                ]);
            }

            $request->validate([
                'user_id'    => ['required'],
                'tipe_ijin'  => ['required', 'in:cuti,sakit dengan surat dokter,ijin potong gaji'],
                'from_date'  => ['required', 'date'],
                'to_date'    => ['required', 'after_or_equal:from_date'],
                'keterangan' => ['required'],
                'lampiran'   => ['required_if:tipe_ijin,sakit dengan surat dokter', 'file', 'mimes:pdf,jpg,jpeg,png'],
            ]);

            $from       = Carbon::parse($request->from_date);
            $to         = Carbon::parse($request->to_date);
            $periode    = CarbonPeriod::create($from, '1 day', $to);
            $total_hari = $from->diffInDays($to) + 1;

            $check_work_day = WorkDay::with('shift')
                ->where('user_id', $request->user_id)
                ->where('tanggal', $from->toDateString())
                ->first();

            if (!$check_work_day) {
                throw new Exception("Kamu tidak memiliki jadwal pada tanggal $from->toDateString()");
            }

            foreach ($periode as $day) {
                $d = $day->toDateString();
                $check_existing_data = DataIjin::where('user_id', $request->user_id)
                    ->where('from_date', '<=', $d)
                    ->where('to_date', '>=', $d)
                    ->first();

                if ($check_existing_data) {
                    throw new Exception("Data ijin pada tanggal $d sudah ada");
                }
            }

            $data_ijin              = new DataIjin();
            $data_ijin->user_id     = $request->user_id;
            $data_ijin->tipe_ijin   = $request->tipe_ijin;
            $data_ijin->from_date   = Carbon::parse($request->from_date)->format('Y-m-d');
            $data_ijin->to_date     = Carbon::parse($request->to_date)->format('Y-m-d');
            $data_ijin->keterangan  = $request->keterangan;
            $data_ijin->is_approved = null;

            $total_hari_ijin = $total_hari;

            if ($from->notEqualTo($to)) {
                while ($from->lt($to)) {
                    $check_work_day = WorkDay::with('shift')
                        ->where('user_id', $request->user_id)
                        ->where('tanggal', $from->toDateString())
                        ->first();

                    if (!$check_work_day) {
                        $total_hari_ijin--; // kurangi total hari jika tidak ada jadwal kerja
                    }

                    // cek hari libur
                    $from->addDay();
                }
            }

            $data_ijin->total_hari = $total_hari;

            $data_user = User::findOrFail($request->user_id)
                ->where('id', $request->user_id)
                ->first();

            if (in_array($request->tipe_ijin, ['cuti']) && $total_hari > $data_user->sisa_cuti) {
                throw new Exception('Sisa cuti tidak mencukupi');
            }

            if ($request->hasFile('lampiran')) {
                $underscore_name     = str_replace(' ', '_', strtolower($request->user()->name));
                $path                = $request->file('lampiran')->store('lampiran_ijin_' . $underscore_name, 'public');
                $data_ijin->lampiran = $path;
            } else {
                $data_ijin->lampiran = null;
            }

            $data_ijin->save();

            DB::commit();
            return redirect()->route('data-ijin.index')->with('success', 'Data Ijin berhasil disimpan');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(DataIjin $dataIjin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DataIjin $dataIjin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DataIjin $dataIjin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DataIjin $dataIjin)
    {
        if ($dataIjin->is_approved) {
            return redirect()->route('data-ijin.index')->with('error', 'Data Ijin yang sudah diapprove tidak bisa dihapus');
        }

        if ($dataIjin->is_approved === false) {
            return redirect()->route('data-ijin.index')->with('error', 'Data Ijin yang sudah ditolak tidak bisa dihapus');
        }

        $dataIjin->delete();

        return redirect()->route('data-ijin.index')->with('success', 'Data Ijin berhasil dihapus');
    }

    public function download($id)
    {
        $data_ijin = DataIjin::findOrFail($id);
        return response()->download(storage_path('app/public/' . $data_ijin->lampiran));
    }

    public function approve_reject(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'id'   => ['required', 'exists:data_ijins,id'],
                'tipe' => ['required', 'in:approve,reject'],
            ]);

            $data_ijin   = DataIjin::findOrFail($request->id);
            $tipe_ijin   = $data_ijin->tipe_ijin->value;
            $total_hari  = $data_ijin->total_hari;
            $is_approved = false;
            $message     = 'Data ijin ditolak.';

            if ($request->tipe == "approve") {
                $data_user = User::findOrFail($data_ijin->user_id);

                if (in_array($tipe_ijin, ['cuti']) && $total_hari > $data_user->sisa_cuti) {
                    throw new Exception('Sisa cuti tidak mencukupi');
                }

                if (in_array($tipe_ijin, ['cuti'])) {
                    $data_user->sisa_cuti -= $total_hari;
                    $data_user->save();
                }

                $is_approved = true;
                $message     = 'Data ijin berhasil diapprove.';
            }

            $data_ijin->update([
                'is_approved' => $is_approved,
                'approved_by' => Auth::id(),
                'approved_at' => Carbon::now(),
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => $message]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
