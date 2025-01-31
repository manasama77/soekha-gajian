<?php

namespace Database\Seeders;

use App\Models\DataKehadiran;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Departement;
use App\Models\PeriodeCutoff;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Role::create([
            'name' => 'admin',
        ]);

        Role::create([
            'name' => 'karyawan',
        ]);

        Departement::create([
            'name' => 'OFFICE',
        ]);

        Departement::create([
            'name' => 'KITCHEN',
        ]);

        Departement::create([
            'name' => 'BAR',
        ]);

        Departement::create([
            'name' => 'CASHIER',
        ]);

        PeriodeCutoff::create([
            'start_date' => '2025-01-01',
            'end_date'   => '2025-01-15',
            'is_active'  => true,
        ]);

        $admin = User::factory()->create([
            'departement_id'     => 1,
            'name'               => 'Admin',
            'email'              => 'admin@soekha.com',
            'join_date'          => '2025-01-01',
            'tipe_gaji'          => null,
            'gaji_pokok'         => 0,
            'gaji_harian'        => 0,
            'whatsapp'           => '082114578976',
            'total_cuti'         => 0,
            'sisa_cuti'          => 0,
            'generate_slip_gaji' => false,
        ]);
        $admin->assignRole('admin');
    }
}
