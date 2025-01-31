<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="dark:text-gray-200 text-xl font-semibold leading-tight text-gray-800">
                {{ __('Periode Cutoff') }}
            </h2>

            <div class="">
                <a href="{{ route('setup.periode-cutoff.create') }}"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Tambah Periode Cutoff
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="sm:px-6 lg:px-8 max-w-full mx-auto">
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
                                        Date Start
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Date End
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <i class="fas fa-cogs"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($periode_cutoffs->isEmpty())
                                    <tr
                                        class="dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b">
                                        <th class="whitespace-nowrap dark:text-white px-6 py-4 font-medium text-center text-gray-900"
                                            colspan="4">
                                            Data tidak ditemukan
                                        </th>
                                    </tr>
                                @endif
                                @foreach ($periode_cutoffs as $periode_cutoff)
                                    <tr
                                        class="dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b">
                                        <td class="px-6 py-4">
                                            {{ $periode_cutoff->start_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $periode_cutoff->end_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $periode_cutoff->status }}
                                        </td>
                                        <td class="px-6 py-4 text-center">

                                            <div class="flex flex-wrap justify-center gap-3">
                                                <div>
                                                    <a href="{{ route('setup.periode-cutoff.edit', $periode_cutoff) }}"
                                                        class="focus:outline-none hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 me-2 dark:focus:ring-yellow-900 px-3 py-2 mb-2 text-sm font-medium text-white bg-yellow-500 rounded-lg">
                                                        <i class="fa-solid fa-pencil me-2"></i>
                                                        Edit
                                                    </a>

                                                    <form id="delete-form-{{ $periode_cutoff->id }}"
                                                        action="{{ route('setup.periode-cutoff.destroy', $periode_cutoff) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <button type="button"
                                                        onclick="askDelete('delete-form-{{ $periode_cutoff->id }}')"
                                                        class="focus:outline-none hover:bg-red-800 focus:ring-4 focus:ring-red-300 me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 px-3 py-2 mb-2 text-sm font-medium text-white bg-red-700 rounded-lg">
                                                        <i class="fa-solid fa-trash me-2"></i>
                                                        Delete
                                                    </button>
                                                </div>

                                                <div>
                                                    <button type="button"
                                                        onclick="askGenerateSlipGaji({{ $periode_cutoff->id }})"
                                                        class="focus:outline-none hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 me-2 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-900 px-3 py-2 mb-2 text-sm font-medium text-white bg-purple-700 rounded-lg">
                                                        <i class="fa-solid fa-file-invoice me-2"></i>
                                                        Generate Slip Gaji
                                                    </button>

                                                    <a href="{{ route('setup.periode-cutoff.excel', $periode_cutoff->id) }}"
                                                        target="_blank"
                                                        class="focus:outline-none hover:bg-green-800 focus:ring-4 focus:ring-green-300 me-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900 px-3 py-2 mb-2 text-sm font-medium text-white bg-green-700 rounded-lg">
                                                        <i class="fa-solid fa-table me-2"></i>
                                                        Table Slip Gaji
                                                    </a>
                                                </div>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $periode_cutoffs->links() }}
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

            function askGenerateSlipGaji(id) {
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: 'Data slip gaji akan digenerate dan akan memakan waktu cukup lama!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Generate!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('setup.periode-cutoff.generate-slip-gaji') }}`,
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                id: id,
                            },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Loading',
                                    html: 'Memproses data...',
                                    allowOutsideClick: false,
                                    showConfirmButton: false,
                                    willOpen: () => {
                                        Swal.showLoading();
                                    },
                                });
                            },
                        }).fail((e) => {
                            console.log(e);
                            Swal.fire({
                                title: 'Error!',
                                text: e.responseJSON.message,
                                icon: 'error',
                            });
                        }).done((e) => {
                            console.log("data", e);

                            if (e.success) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: e.message,
                                    icon: 'success',
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: e.message,
                                    icon: 'error',
                                });
                            }
                        })
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
