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
            @if (session('success'))
                <div class="relative px-4 py-3 mt-4 text-green-700 bg-green-100 border border-green-400 rounded"
                    role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="sm:inline block">{{ session('success') }}</span>
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

                    {{-- <button data-modal-target="default-modal" data-modal-toggle="default-modal"
                        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        type="button">
                        Toggle modal
                    </button> --}}

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

    @push('modals')
        <div id="modalEl" tabindex="-1" aria-hidden="true"
            class="fixed left-0 right-0 top-0 z-50 hidden h-[calc(100%-1rem)] max-h-full w-full overflow-y-auto overflow-x-hidden p-4 md:inset-0">
            <div class="relative w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="dark:bg-gray-700 relative bg-white rounded-lg shadow-sm">
                    <!-- Modal header -->
                    <div class="dark:border-gray-600 flex items-start justify-between p-5 border-b rounded-t">
                        <h3 class="dark:text-white lg:text-2xl text-xl font-semibold text-gray-900">
                            Terms of Service
                        </h3>
                        <button type="button"
                            class="ms-auto hover:bg-gray-200 hover:text-gray-900 dark:hover:bg-gray-600 dark:hover:text-white inline-flex items-center justify-center w-8 h-8 text-sm text-gray-400 bg-transparent rounded-lg">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <p class="dark:text-gray-400 text-base leading-relaxed text-gray-500">
                            With less than a month to go before the European Union
                            enacts new consumer privacy laws for its citizens, companies
                            around the world are updating their terms of service
                            agreements to comply.
                        </p>
                        <p class="dark:text-gray-400 text-base leading-relaxed text-gray-500">
                            The European Union’s General Data Protection Regulation
                            (G.D.P.R.) goes into effect on May 25 and is meant to ensure
                            a common set of data rights in the European Union. It
                            requires organizations to notify users as soon as possible
                            of high-risk data breaches that could personally affect
                            them.
                        </p>
                    </div>
                    <!-- Modal footer -->
                    <div
                        class="rtl:space-x-reverse dark:border-gray-600 flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                        <button type="button"
                            class="rounded-lg bg-blue-700 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            I accept
                        </button>
                        <button type="button"
                            class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-900 focus:z-10 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:border-gray-500 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white">
                            Decline
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endpush


    @push('scripts')
        <script>
            let modal;

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
