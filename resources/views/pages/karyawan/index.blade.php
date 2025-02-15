<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="dark:text-gray-200 text-xl font-semibold leading-tight text-gray-800">
                {{ __('Karyawan') }}
            </h2>

            <div class="">
                <a href="{{ route('setup.karyawan.create') }}"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Tambah Karyawan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-1">
        <div class="max-w-full mx-auto">
            @if (session('success'))
                <div class="relative px-4 py-3 mt-4 mb-3 text-green-700 bg-green-100 border border-green-400 rounded"
                    role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="sm:inline block">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="relative px-4 py-3 mt-4 mb-3 text-red-700 bg-red-100 border border-red-400 rounded"
                    role="alert">
                    <strong class="font-bold">Error!</strong>
                    <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                </div>
            @endif

            <div class="flex items-center mb-4">
                <form action="{{ route('setup.karyawan.index') }}" method="GET" class="md:max-w-sm w-full">
                    <div class="flex">
                        <input type="text" name="search" placeholder="Cari Karyawan"
                            class="dark:bg-gray-700 dark:text-gray-300 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full px-4 py-2 border border-gray-300 rounded-lg"
                            value="{{ $search }}" />
                        <button type="submit"
                            class="ml-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            Cari
                        </button>
                    </div>
                </form>
            </div>

            <div class="sm:grid-cols-1 lg:grid-cols-2 grid grid-cols-1 gap-4 mx-auto mb-3">

                @if ($karyawans->isEmpty())
                    <div
                        class="dark:bg-gray-800 dark:border-gray-700 w-full col-span-2 p-6 bg-white border border-gray-200 rounded-lg shadow">
                        <h5
                            class="dark:text-white px-3 mb-2 text-2xl font-bold tracking-tight text-center text-gray-900">
                            Data Karyawan Kosong
                        </h5>
                    </div>
                @endif

                @foreach ($karyawans as $karyawan)
                    <div
                        class="dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 w-full p-6 bg-white border border-gray-200 rounded-lg shadow">
                        <a href="{{ route('setup.karyawan.edit', $karyawan) }}">
                            <h5 class="dark:text-white px-3 mb-2 text-2xl font-bold tracking-tight text-gray-900">
                                {{ $karyawan->name }} {{ $karyawan->getRoleNames()->first() }}
                            </h5>
                            <h6 class="dark:text-white px-3 mb-2 text-xl font-bold tracking-tight text-gray-900">
                                Departemen {{ $karyawan->departement->name }}
                            </h6>
                        </a>

                        <table class="dark:text-gray-400 mb-3 text-sm text-left text-gray-500">
                            <tbody>
                                <tr>
                                    <td class="px-3 py-2">Email</td>
                                    <td class="px-3 py-2">:</td>
                                    <td class="px-3 py-2">
                                        {{ $karyawan->email }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2">WhatsApp</td>
                                    <td class="px-3 py-2">:</td>
                                    <td class="px-3 py-2">
                                        {{ $karyawan->whatsapp }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2">Join Date</td>
                                    <td class="px-3 py-2">:</td>
                                    <td class="px-3 py-2">
                                        {{ $karyawan->join_date->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2">Gaji</td>
                                    <td class="px-3 py-2">:</td>
                                    <td class="px-3 py-2">
                                        @if ($karyawan->tipe_gaji == 'bulanan')
                                            {{ $karyawan->gaji_pokok_idr }} per Bulan
                                        @else
                                            {{ $karyawan->gaji_harian_idr }} per Hari
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2">Gaji Perbantuan Shift</td>
                                    <td class="px-3 py-2">:</td>
                                    <td class="px-3 py-2">
                                        {{ $karyawan->gaji_perbantuan_shift_idr }} per Hari
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-3 py-2">Cuti</td>
                                    <td class="px-3 py-2">:</td>
                                    <td class="px-3 py-2">
                                        {{ $karyawan->total_cuti }} / {{ $karyawan->sisa_cuti }} Hari
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="md:justify-around flex flex-wrap items-center justify-center gap-5 mt-10">
                            <div>
                                <a href="{{ route('setup.karyawan.edit', $karyawan) }}"
                                    class="focus:outline-none text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">
                                    <i class="fa-solid fa-pencil me-2"></i>
                                    Edit
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('setup.karyawan.reset-password', $karyawan) }}"
                                    class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900">
                                    <i class="fa-solid fa-key me-2"></i>
                                    Reset
                                </a>

                            </div>
                            <div>
                                <form id="delete-form-{{ $karyawan->id }}"
                                    action="{{ route('setup.karyawan.destroy', $karyawan) }}" method="POST"
                                    class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" onclick="askDelete('delete-form-{{ $karyawan->id }}')"
                                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 inline">
                                    <i class="fa-solid fa-trash me-2"></i>
                                    Delete
                                </button>
                            </div>
                            <div>
                                <a href="{{ $karyawan->whatsapp_link }}" target="_blank"
                                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">
                                    <i class="fa-brands fa-whatsapp me-2"></i>
                                    WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{ $karyawans->links() }}

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function askDelete(formId) {
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: 'Data yang dihapus tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
