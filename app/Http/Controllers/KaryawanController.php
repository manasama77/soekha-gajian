<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Enums\TipeGaji;
use App\Models\Karyawan;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $karyawans = User::orderBy('created_at', 'desc')
            ->where('generate_slip_gaji', true);

        if ($request->has('search') && !is_null($request->search)) {
            $karyawans->where('name', 'like', '%' . $request->search . '%');
        }

        $karyawans = $karyawans->paginate(10)->appends($request->all());

        $data = [
            'search'    => $request->search,
            'karyawans' => $karyawans,
        ];


        return view('pages.karyawan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departements = Departement::all();

        return view('pages.karyawan.create', compact('departements'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'email'                 => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->whereNull('deleted_at'),
                    'max:255'
                ],
                'password'              => ['required', 'string', 'min:8', 'confirmed'],
                'departement_id'        => ['required', 'exists:departements,id'],
                'name'                  => ['required', 'string', 'max:255'],
                'tipe_gaji'             => ['required', 'in:' . TipeGaji::Bulanan->value . ',' . TipeGaji::Harian->value],
                'gaji_pokok'            => ['required', 'numeric'],
                'gaji_harian'           => [
                    'required_if:tipe_gaji,' . TipeGaji::Harian->value,
                    'numeric',
                ],
                'gaji_perbantuan_shift' => ['required', 'numeric'],
                'join_date'             => ['required', 'date'],
                'total_cuti'            => ['nullable', 'numeric'],
                'sisa_cuti'             => ['nullable', 'numeric'],
                'whatsapp'              => ['required', 'string', 'max:255'],
                'is_admin'              => ['nullable', 'boolean'],
            ]);

            $gaji_pokok  = 0;
            $gaji_harian = 0;

            if ($request->tipe_gaji == 'bulanan') {
                $gaji_pokok  = $request->gaji_pokok;
            } else {
                $gaji_harian = $request->gaji_harian;
            }

            $user = User::createOrFirst([
                'departement_id'        => $request->departement_id,
                'name'                  => $request->name,
                'email'                 => $request->email,
                'password'              => bcrypt($request->password),
                'join_date'             => $request->join_date,
                'tipe_gaji'             => $request->tipe_gaji,
                'gaji_pokok'            => $gaji_pokok,
                'gaji_harian'           => $gaji_harian,
                'gaji_perbantuan_shift' => $request->gaji_perbantuan_shift,
                'whatsapp'              => $request->whatsapp,
                'total_cuti'            => $request->total_cuti ?? 0,
                'sisa_cuti'             => $request->sisa_cuti ?? 0,
                'generate_slip_gaji'    => true,
            ]);
            if ($request->is_admin) {
                $user->assignRole('admin');
            } else {
                $user->assignRole('karyawan');
            }

            DB::commit();
            return redirect()->route('setup.karyawan.index')->with('success', 'Karyawan created successfully !');
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($path)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $departements = Departement::all();
        return view('pages.karyawan.edit', compact('user', 'departements'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'email'                 => ['required', 'email:rfc,dns', 'max:255'],
            'departement_id'        => ['required', 'exists:departements,id'],
            'name'                  => ['required', 'string', 'max:255'],
            'tipe_gaji'             => ['required', 'in:' . TipeGaji::Bulanan->value . ',' . TipeGaji::Harian->value],
            'gaji_pokok'            => ['required', 'numeric'],
            'gaji_harian'           => [
                'required_if:tipe_gaji,' . TipeGaji::Harian->value,
                'numeric'
            ],
            'gaji_perbantuan_shift' => ['required', 'numeric'],
            'join_date'             => ['required', 'date'],
            'total_cuti'            => ['nullable', 'numeric'],
            'sisa_cuti'             => ['nullable', 'numeric'],
            'whatsapp'              => ['required', 'string', 'max:255'],
            'is_admin'              => ['nullable', 'boolean'],
        ]);

        $gaji_pokok  = 0;
        $gaji_harian = 0;

        if ($request->tipe_gaji == 'bulanan') {
            $gaji_pokok  = $request->gaji_pokok;
        } else {
            $gaji_harian = $request->gaji_harian;
        }

        if ($request->is_admin) {
            $user->assignRole('admin');
        } else {
            $user->assignRole('karyawan');
        }

        $user = $user->update([
            'departement_id'        => $request->departement_id,
            'name'                  => $request->name,
            'tipe_gaji'             => $request->tipe_gaji,
            'gaji_pokok'            => $gaji_pokok,
            'gaji_harian'           => $gaji_harian,
            'gaji_perbantuan_shift' => $request->gaji_perbantuan_shift,
            'join_date'             => $request->join_date,
            'total_cuti'            => $request->total_cuti ?? 0,
            'sisa_cuti'             => $request->sisa_cuti ?? 0,
            'whatsapp'              => $request->whatsapp,
        ]);

        return redirect()->route('setup.karyawan.index')->with('success', 'Karyawan updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->removeRole('karyawan');
        $user->delete();
        return redirect()->route('setup.karyawan.index')->with('success', 'Karyawan deleted successfully !');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function reset_password(User $user)
    {
        $departements = Departement::all();
        return view('pages.karyawan.reset_password', compact('user', 'departements'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function reset_password_process(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('setup.karyawan.index')->with('success', 'Reset password successfully !');
    }
}
