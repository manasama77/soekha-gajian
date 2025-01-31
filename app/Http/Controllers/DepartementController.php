<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $departements = Departement::query();

        if ($request->has('search') && !is_null($request->search)) {
            $departements->where('name', 'like', '%' . $request->search . '%');
        }

        $departements = $departements->paginate(10)->appends($request->query());

        $data = [
            'search'       => $request->search,
            'departements' => $departements,
        ];

        return view('pages.departements.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.departements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Departement::create([
            'name' => $request->name,
        ]);

        return redirect()->route('setup.departements.index')->with('success', 'Departement created successfully !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Departement $departement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departement $departement)
    {
        return view('pages.departements.edit', compact('departement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Departement $departement)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $departement->update([
            'name' => $request->name,
        ]);

        return redirect()->route('setup.departements.index')->with('success', 'Departement updated successfully !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departement $departement)
    {
        try {
            DB::beginTransaction();

            $get_karyawan = $departement->karyawans()->get();

            foreach ($get_karyawan as $karyawan) {
                $karyawan->user()->delete();
                $karyawan->delete();
            }

            $departement->delete();

            DB::commit();

            return redirect()->route('setup.departements.index')->with('success', 'Departement deleted successfully !');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('setup.departements.index')->with('error', 'Failed to delete departement: ' . $e->getMessage());
        }
    }
}
