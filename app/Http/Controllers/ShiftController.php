<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $shifts = Shift::paginate(10);

        $data = [
            'search' => $request->search,
            'shifts' => $shifts,
        ];

        return view('pages.shifts.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.shifts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'start_time'          => ['required', 'date_format:H:i'],
            'end_time'            => ['required', 'date_format:H:i', 'after:start_time'],
            'is_perbantuan_shift' => ['required'],
        ]);

        Shift::create([
            'name'                => $request->name,
            'start_time'          => $request->start_time,
            'end_time'            => $request->end_time,
            'is_perbantuan_shift' => $request->is_perbantuan_shift,
        ]);

        return redirect()->route('setup.shifts.index')->with('success', 'Shift created successfully !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shift $shift)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shift $shift)
    {
        return view('pages.shifts.edit', compact('shift'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'start_time'          => ['required', 'date_format:H:i'],
            'end_time'            => ['required', 'date_format:H:i', 'after:start_time'],
            'is_perbantuan_shift' => ['required'],
        ]);

        $shift->update([
            'name'                => $request->name,
            'start_time'          => $request->start_time,
            'end_time'            => $request->end_time,
            'is_perbantuan_shift' => $request->is_perbantuan_shift,
        ]);

        return redirect()->route('setup.shifts.index')->with('success', 'Shift updated successfully !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        try {
            DB::beginTransaction();

            $shift->delete();

            DB::commit();

            return redirect()->route('setup.shifts.index')->with('success', 'Shift deleted successfully !');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('setup.shifts.index')->withErrors('Failed to delete Shift: ' . $e->getMessage());
        }
    }
}
