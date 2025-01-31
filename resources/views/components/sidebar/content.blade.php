<x-perfect-scrollbar as="nav" aria-label="main" class="flex flex-col flex-1 gap-4 px-3">

    <x-sidebar.link title="Dashboard" href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
        <x-slot name="icon">
            <x-icons.dashboard class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
        </x-slot>
    </x-sidebar.link>

    @if (auth()->user()->hasRole('admin'))
        <x-sidebar.dropdown title="Setup" :active="Str::startsWith(request()->route()->uri(), 'setup')">
            <x-slot name="icon">
                <x-icons.cog-6-tooth class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            </x-slot>

            <x-sidebar.sublink title="Departement" href="{{ route('setup.departements.index') }}" :active="Str::startsWith(request()->route()->uri(), 'setup/departements')" />
            <x-sidebar.sublink title="Karyawan" href="{{ route('setup.karyawan.index') }}" :active="Str::startsWith(request()->route()->uri(), 'setup/karyawan')" />
            <x-sidebar.sublink title="Periode Cutoff" href="{{ route('setup.periode-cutoff.index') }}"
                :active="Str::startsWith(request()->route()->uri(), 'setup/periode-cutoff')" />
            <x-sidebar.sublink title="Shift" href="{{ route('setup.shifts.index') }}" :active="Str::startsWith(request()->route()->uri(), 'setup/shifts')" />
            <x-sidebar.sublink title="Work Day" href="{{ route('setup.work-days.index') }}" :active="Str::startsWith(request()->route()->uri(), 'setup/work-days')" />
        </x-sidebar.dropdown>
    @endif

    <x-sidebar.link title="Kehadiran" href="{{ route('data-kehadiran.index') }}" :isActive="Str::startsWith(request()->route()->uri(), 'data-kehadiran')">
        <x-slot name="icon">
            <i class="fa-solid fa-camera text-[1.3rem]" aria-hidden="true"></i>
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.link title="Request Kehadiran" href="{{ route('request-kehadiran.index') }}" :isActive="Str::startsWith(request()->route()->uri(), 'request-kehadiran')">
        <x-slot name="icon">
            <i class="fa-solid fa-hand-point-up text-[1.3rem]" aria-hidden="true"></i>
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.link title="Lembur" href="{{ route('data-lembur.index') }}" :isActive="Str::startsWith(request()->route()->uri(), 'data-lembur')">
        <x-slot name="icon">
            <i class="fa-solid fa-cloud-moon text-[1.3rem]" aria-hidden="true"></i>
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.link title="Ijin" href="{{ route('data-ijin.index') }}" :isActive="Str::startsWith(request()->route()->uri(), 'data-ijin')">
        <x-slot name="icon">
            <i class="fa-solid fa-bed text-[1.3rem]" aria-hidden="true"></i>
        </x-slot>
    </x-sidebar.link>

    <x-sidebar.link title="Slip Gaji" href="{{ route('slip-gaji.index') }}" :isActive="Str::startsWith(request()->route()->uri(), 'slip-gaji')">
        <x-slot name="icon">
            <i class="fa-solid fa-file-invoice-dollar text-[1.3rem]" aria-hidden="true"></i>
        </x-slot>
    </x-sidebar.link>

</x-perfect-scrollbar>
