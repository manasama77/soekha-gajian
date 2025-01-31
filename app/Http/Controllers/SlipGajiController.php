<?php

namespace App\Http\Controllers;

use App\Models\SlipGaji;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SlipGajiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $slip_gajis = SlipGaji::with([
            'periode_cutoff',
            'karyawan',
        ]);

        if (Auth::user()->hasRole('karyawan')) {
            $slip_gajis->where('karyawan_id', Auth::user()->karyawan->id);
        }

        $slip_gajis = $slip_gajis->orderBy('id', 'desc')->paginate(10)->withQueryString();

        $data = [
            'slip_gajis' => $slip_gajis,
        ];

        return view('pages.slip_gaji.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SlipGaji $slipGaji)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SlipGaji $slipGaji)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SlipGaji $slipGaji)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SlipGaji $slipGaji)
    {
        //
    }

    public function download(SlipGaji $slipGaji)
    {
        if (Auth::user()->hasRole('karyawan') && $slipGaji->karyawan_id != Auth::user()->karyawan->id) {
            return redirect()->route('slip-gaji.index')->withErrors('Anda tidak memiliki akses ke slip gaji ini');
        }
        return response()->download(public_path('storage/slip_gaji/' . $slipGaji->file_pdf));
    }
}
