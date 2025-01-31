<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="dark:text-gray-200 text-xl font-semibold leading-tight text-gray-800">
                {{ __('Work Day') }}
            </h2>

            <div class="">
                <a href="{{ route('setup.work-days.create') }}"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Tambah Work Day
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="md:max-w-5xl sm:px-6 lg:px-8 max-w-full mx-auto">
            @if (session('status'))
                <div class="relative px-4 py-3 mt-4 text-green-700 bg-green-100 border border-green-400 rounded"
                    role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="sm:inline block">{{ session('status') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="relative px-4 py-3 mt-4 text-red-700 bg-red-100 border border-red-400 rounded"
                    role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="sm:inline block">{{ session('error') }}</span>
                </div>
            @endif

            <div class="dark:bg-gray-800 sm:rounded-lg overflow-hidden bg-white shadow-sm">
                <div class="dark:text-gray-100 p-6 text-gray-900">

                    <div class="sm:rounded-lg relative overflow-x-auto shadow-md">
                        <table id="tables" class="dark:text-gray-400 w-full text-sm text-left text-gray-500">
                            <thead
                                class="bg-gray-50 dark:bg-gray-700 dark:text-gray-400 text-xs text-gray-700 uppercase">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Periode
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Karyawan
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Total Kerja
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Total Libur
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <i class="fas fa-cogs"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($work_days->isEmpty())
                                    <tr
                                        class="dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b">
                                        <th class="whitespace-nowrap dark:text-white px-6 py-4 font-medium text-center text-gray-900"
                                            colspan="4">
                                            Data tidak ditemukan
                                        </th>
                                    </tr>
                                @endif
                                @foreach ($work_days as $work_day)
                                    <tr
                                        class="dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b">
                                        <td class="px-6 py-4">
                                            {{ $work_day->periode_cutoff->start_date->format('d M Y') }} -
                                            {{ $work_day->periode_cutoff->end_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $work_day->user->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $work_day->total_hari_kerja }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $work_day->total_hari_tidak_kerja }}
                                        </td>
                                        <td class="px-6 py-4 text-center">

                                            <div class="flex flex-wrap justify-center gap-1">
                                                <a href="{{ route('setup.work-days.show', [$work_day->periode_cutoff_id, $work_day->user_id]) }}"
                                                    class="focus:outline-none text-white bg-blue-500 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:focus:ring-blue-900"
                                                    title="Detail">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>

                                                <a href="{{ route('setup.work-days.edit', [$work_day->periode_cutoff_id, $work_day->user_id]) }}"
                                                    class="focus:outline-none text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:focus:ring-yellow-900"
                                                    title="Edit">
                                                    <i class="fa-solid fa-pencil"></i>
                                                </a>

                                                <form
                                                    id="delete-form-{{ $work_day->periode_cutoff_id }}-{{ $work_day->user_id }}"
                                                    action="{{ route('setup.work-days.destroy', [$work_day->periode_cutoff_id, $work_day->user_id]) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button"
                                                    onclick="askDelete('delete-form-{{ $work_day->periode_cutoff_id }}-{{ $work_day->user_id }}')"
                                                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                                    title="Delete">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $work_days->links() }}
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
