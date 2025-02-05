<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="dark:text-gray-200 text-xl font-semibold leading-tight text-gray-800">
                {{ __('Data Presensi') }}
            </h2>

            <div class="">
                <a href="{{ route('data-kehadiran.create') }}"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Tambah Data Presensi
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="sm:px-6 lg:px-8 max-w-full mx-auto">
            @if (session('success'))
                <div class="relative px-4 py-3 mt-4 text-green-700 bg-green-100 border border-green-400 rounded"
                    role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="sm:inline block">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="relative px-4 py-3 mt-4 text-red-700 bg-red-100 border border-red-400 rounded"
                    role="alert">
                    <strong class="font-bold">Error!</strong>
                    <ul class="mt-2 text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="dark:bg-gray-800 sm:rounded-lg overflow-hidden bg-white shadow-sm">
                <div class="dark:text-gray-100 p-6 text-gray-900">

                    <div class="flex items-center justify-start max-w-full mb-4">
                        <form action="{{ route('data-kehadiran.index') }}" method="GET" class="md:max-w-xl w-full">
                            <div class="flex flex-wrap gap-3">
                                @if (auth()->user()->hasRole('admin'))
                                    <div>
                                        <select id="user_id" name="user_id"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 min-w-48">
                                            <option @selected($user_id == null) value="">Semua Karyawan</option>
                                            @foreach ($users as $user)
                                                <option @selected($user_id == $user->id) value="{{ $user->id }}">
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div>
                                    <select id="bulan" name="bulan"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option @selected($bulan == null) value="">Semua Bulan</option>
                                        @foreach ($bulans as $b)
                                            <option @selected($bulan == $b['month_id']) value="{{ $b['month_id'] }}">
                                                {{ $b['month_name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <select id="tahun" name="tahun"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        @foreach ($tahuns as $b)
                                            <option @selected($tahun == $b['year']) value="{{ $b['year'] }}">
                                                {{ $b['year'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <button type="submit"
                                        class="ml-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                        Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="sm:rounded-lg relative overflow-x-auto shadow-md">
                        <table id="tables" class="dark:text-gray-400 w-full text-sm text-left text-gray-500">
                            <thead
                                class="bg-gray-50 dark:bg-gray-700 dark:text-gray-400 text-xs text-gray-700 uppercase">
                                <tr>
                                    @if (auth()->user()->hasRole('admin'))
                                        <th scope="col" class="px-6 py-3">
                                            Karyawan
                                        </th>
                                    @endif
                                    <th scope="col" class="px-6 py-3">
                                        Tanggal
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Shift
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        Kehadiran
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        Pulang
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Terlambat
                                    </th>
                                    @if (auth()->user()->hasRole('admin'))
                                        <th scope="col" class="px-6 py-3 text-center">
                                            <i class="fas fa-cogs"></i>
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($data_kehadirans->isEmpty())
                                    <tr
                                        class="dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b">
                                        <th class="whitespace-nowrap dark:text-white px-6 py-4 font-medium text-center text-gray-900"
                                            colspan="{{ auth()->user()->hasRole('admin') ? 7 : 5 }}">
                                            Data tidak ditemukan
                                        </th>
                                    </tr>
                                @endif
                                @foreach ($data_kehadirans as $data_kehadiran)
                                    <tr
                                        class="dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b">
                                        @if (auth()->user()->hasRole('admin'))
                                            <td class="px-6 py-4">
                                                {{ $data_kehadiran->user->name }}
                                            </td>
                                        @endif
                                        <td class="px-6 py-4">
                                            {{ $data_kehadiran->tanggal->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $data_kehadiran->shifts->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-3">
                                                <div>
                                                    {{ $data_kehadiran->clock_in }}
                                                </div>
                                                <div>
                                                    @if ($data_kehadiran->foto_in && $data_kehadiran->foto_in != '#')
                                                        <a href="{{ route('data-kehadiran.show', ['path' => $data_kehadiran->foto_in]) }}"
                                                            class="focus:outline-none hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 dark:focus:ring-yellow-900 px-2 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg"
                                                            target="_blank">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex justify-center gap-3">
                                                <div>
                                                    {{ $data_kehadiran->clock_out }}
                                                </div>
                                                <div>
                                                    @if ($data_kehadiran->foto_out && $data_kehadiran->foto_out != '#')
                                                        <a href="{{ route('data-kehadiran.show', ['path' => $data_kehadiran->foto_out]) }}"
                                                            class="focus:outline-none hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 dark:focus:ring-yellow-900 px-2 py-2 text-sm font-medium text-white bg-yellow-500 rounded-lg"
                                                            target="_blank">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $data_kehadiran->menit_terlambat }} Menit
                                        </td>
                                        @if (auth()->user()->hasRole('admin'))
                                            <td class="px-6 py-4">
                                                <div class="flex justify-center gap-3">
                                                    <div>
                                                        <form id="delete-form-{{ $data_kehadiran->id }}"
                                                            action="{{ route('data-kehadiran.destroy', $data_kehadiran) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                        <button type="button"
                                                            onclick="askDelete('delete-form-{{ $data_kehadiran->id }}')"
                                                            class="focus:outline-none hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 px-2 py-2 text-sm font-medium text-white bg-red-700 rounded-lg">
                                                            <i class="fa-solid fa-trash fa-fw"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $data_kehadirans->links() }}
                    </div>
                </div>
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
