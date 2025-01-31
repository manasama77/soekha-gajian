<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\DataIjin;
use App\Models\Karyawan;
use App\Models\HariLibur;
use Illuminate\Http\Request;
use App\Models\PeriodeCutoff;
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
            $data_ijins->where('karyawan_id', Auth::user()->karyawan->id);
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
        $karyawans = Karyawan::query();

        if (Auth::user()->hasRole('karyawan')) {
            $karyawans->where('id', Auth::user()->karyawan->id);
        }

        $karyawans = $karyawans->get();

        $periode_cutoff = PeriodeCutoff::active()->first();

        $min_date = $periode_cutoff->kehadiran_start->toDateString();
        $max_date = $periode_cutoff->kehadiran_end->toDateString();

        $data = [
            'karyawans' => $karyawans,
            'min_date'  => $min_date,
            'max_date'  => $max_date,
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
                    'karyawan_id' => Auth::user()->karyawan->id,
                ]);
            }

            $request->validate([
                'karyawan_id' => ['required'],
                'tipe_ijin'   => ['required', 'in:cuti,sakit dengan surat dokter,ijin potong gaji'],
                'from_date'   => ['required', 'date'],
                'to_date'     => ['required', 'after_or_equal:from_date'],
                'keterangan'  => ['required'],
                'lampiran'    => ['required_if:tipe_ijin,cuti,sakit dengan surat dokter', 'file', 'mimes:pdf,jpg,jpeg,png'],
            ]);

            $from = Carbon::parse($request->from_date);
            $to   = Carbon::parse($request->to_date);

            $check_hari_libur_from = HariLibur::whereDate('tanggal', $from->toDateString())->first();

            if ($check_hari_libur_from) {
                throw new Exception('Tanggal ' . $from->format('d-m-Y') . ' adalah hari libur');
            }

            $data_ijin              = new DataIjin();
            $data_ijin->karyawan_id = $request->karyawan_id;
            $data_ijin->tipe_ijin   = $request->tipe_ijin;
            $data_ijin->from_date   = Carbon::parse($request->from_date)->format('Y-m-d');
            $data_ijin->to_date     = Carbon::parse($request->to_date)->format('Y-m-d');
            $data_ijin->keterangan  = $request->keterangan;
            $data_ijin->is_approved = null;

            $total_hari = 1;

            if ($from->notEqualTo($to)) {
                while ($from->lt($to)) {
                    if (!$from->isSunday()) {
                        $total_hari++;
                    }

                    $cek_hari_libur = HariLibur::whereDate('tanggal', $from->toDateString())->first();

                    if ($cek_hari_libur) {
                        $total_hari--; // kurangi total hari jika ada hari libur
                    }

                    // cek hari libur
                    $from->addDay();
                }
            }

            $data_ijin->total_hari = $total_hari;

            $data_karyawan = Karyawan::findOrFail($request->karyawan_id)
                ->where('id', $request->karyawan_id)
                ->first();

            if (in_array($request->tipe_ijin, ['cuti']) && $total_hari > $data_karyawan->sisa_cuti) {
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
            $tipe_ijin   = $data_ijin->tipe_ijin;
            $total_hari  = $data_ijin->total_hari;
            $is_approved = false;
            $message     = 'Data ijin ditolak.';

            if ($request->tipe == "approve") {
                $data_karyawan = Karyawan::findOrFail($data_ijin->karyawan_id);

                if (in_array($tipe_ijin, ['cuti']) && $total_hari > $data_karyawan->sisa_cuti) {
                    throw new Exception('Sisa cuti tidak mencukupi');
                }

                if (in_array($tipe_ijin, ['cuti'])) {
                    $data_karyawan->sisa_cuti -= $total_hari;
                    $data_karyawan->save();
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
