<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Shift;
use App\Models\WorkDay;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Models\PeriodeCutoff;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WorkDayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $work_days = WorkDay::select(['user_id', 'periode_cutoff_id'])
            ->selectRaw('COUNT(CASE WHEN is_off_day = false THEN 1 END) as total_hari_kerja')
            ->selectRaw('COUNT(CASE WHEN is_off_day = true THEN 1 END) as total_hari_tidak_kerja')
            ->with(['user', 'periode_cutoff'])
            ->groupBy('user_id', 'periode_cutoff_id')
            ->paginate(10);

        $data = [
            'work_days' => $work_days,
        ];

        return view('pages.work_days.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periode_cutoffs = PeriodeCutoff::orderBy('is_active', 'desc')->get();
        $users           = User::where('generate_slip_gaji', true)->get();
        $shifts          = Shift::all();

        $data = [
            'periode_cutoffs' => $periode_cutoffs,
            'users'           => $users,
            'shifts'          => $shifts,
        ];

        return view('pages.work_days.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'user_id'           => ['required', 'exists:users,id'],
                'periode_cutoff_id' => ['required', 'exists:periode_cutoffs,id'],
                'tanggal.*'         => ['required', 'date'],
                'shift_id.*'        => ['required', 'integer'],
            ]);

            foreach ($request->shift_id as $key => $shift_id) {
                $check = WorkDay::where('user_id', $request->user_id)
                    ->where('periode_cutoff_id', $request->periode_cutoff_id)
                    ->where('shift_id', $shift_id)
                    ->first();

                if ($check) {
                    throw new Exception('Shift sudah ada', 422);
                }
            }

            foreach ($request->tanggal as $key => $tanggal) {
                $work_day = new WorkDay();
                $work_day->periode_cutoff_id = $request->periode_cutoff_id;
                $work_day->user_id           = $request->user_id;
                $work_day->tanggal           = $tanggal;
                $work_day->shift_id          = $request->shift_id[$key];
                if ($shift_id == 0) {
                    $work_day->is_off_day = 1;
                }
                $work_day->save();
            }

            DB::commit();
            return response()->json([
                'message' => 'WorkDay created successfully',
                'data'    => $request->all(),
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error creating WorkDay',
                'error'   => $e->getMessage(),
            ], $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($periode_cutoff_id, $user_id)
    {
        $periode_cutoffs = PeriodeCutoff::find($periode_cutoff_id);

        $users = User::find($user_id);

        $work_days = WorkDay::where('periode_cutoff_id', $periode_cutoff_id)
            ->where('user_id', $user_id)
            ->orderBy('tanggal', 'asc')
            ->get();

        $data = [
            'periode_cutoffs' => $periode_cutoffs,
            'users'           => $users,
            'work_days'       => $work_days,
        ];

        return view('pages.work_days.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($periode_cutoff_id, $user_id)
    {
        $periode_cutoffs = PeriodeCutoff::orderBy('is_active', 'desc')->get();
        $users           = User::where('generate_slip_gaji', true)->get();
        $shifts          = Shift::all();
        $work_days = WorkDay::where('periode_cutoff_id', $periode_cutoff_id)
            ->where('user_id', $user_id)
            ->orderBy('tanggal', 'asc')
            ->get();

        $data = [
            'periode_cutoffs'   => $periode_cutoffs,
            'users'             => $users,
            'shifts'            => $shifts,
            'periode_cutoff_id' => $periode_cutoff_id,
            'user_id'           => $user_id,
            'work_days'         => $work_days,
        ];

        return view('pages.work_days.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $periode_cutoff_id, $user_id)
    {
        try {
            DB::beginTransaction();

            $request->merge([
                'periode_cutoff_id' => $periode_cutoff_id,
                'user_id'           => $user_id,
            ]);

            $request->validate([
                'user_id'           => ['required', 'exists:users,id'],
                'periode_cutoff_id' => ['required', 'exists:periode_cutoffs,id'],
                'id.*'              => ['required'],
                'tanggal.*'         => ['required'],
                'shift_id.*'        => ['required'],
            ]);

            foreach ($request->id as $key => $id) {
                $work_day = WorkDay::find($id);
                $work_day->periode_cutoff_id = $request->periode_cutoff_id;
                $work_day->user_id           = $request->user_id;
                $work_day->tanggal           = $request->tanggal[$key];
                $work_day->shift_id          = $request->shift_id[$key];
                if ($request->shift_id[$key] == 1) {
                    $work_day->is_off_day = 1;
                } else {
                    $work_day->is_off_day = 0;
                }
                $work_day->save();
            }

            DB::commit();
            return response()->json([
                'message' => 'WorkDay updated successfully',
            ], 200);
        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error',
                'errors'  => [$e->getMessage()],
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($periode_cutoff_id, $user_id)
    {
        try {
            DB::beginTransaction();

            WorkDay::where('periode_cutoff_id', $periode_cutoff_id)
                ->where('user_id', $user_id)
                ->delete();

            DB::commit();

            return redirect()->route('setup.work-days.index')->with('success', 'Work Day deleted successfully !');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('setup.work-days.index')->withErrors('Failed to delete Work Day: ' . $e->getMessage());
        }
    }

    public function generate_period($id)
    {
        $periode_cutoffs = PeriodeCutoff::find($id);

        if (!$periode_cutoffs) {
            return response()->json([
                'message' => 'Periode Cutoff not found',
                'data'    => [],
            ], 404);
        }

        $start_date = $periode_cutoffs->start_date;
        $end_date   = $periode_cutoffs->end_date;

        // create carbon period
        $periods = CarbonPeriod::create($start_date, $end_date);

        $arr_work_day = [];
        foreach ($periods as $period) {
            $tanggal = $period->toDateString();
            $arr_work_day[] = $tanggal;
        }

        return response()->json([
            'message' => 'Success',
            'data'    => $arr_work_day,
        ], 200);
    }
}
