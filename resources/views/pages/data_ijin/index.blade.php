<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="dark:text-gray-200 text-xl font-semibold leading-tight text-gray-800">
                {{ __('Data Ijin') }}
            </h2>

            <div class="">
                <a href="{{ route('data-ijin.create') }}"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Tambah Data Ijin
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

            <div class="dark:bg-gray-800 sm:rounded-lg overflow-hidden bg-white shadow-sm">
                <div class="dark:text-gray-100 p-6 text-gray-900">

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
                                        Tipe Ijin
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        From
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        To
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Total Hari
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Keterangan
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    @if (auth()->user()->hasRole('admin'))
                                        <th scope="col" class="px-6 py-3 text-center">
                                            <i class="fas fa-cog"></i>
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($data_ijins->isEmpty())
                                    <tr
                                        class="dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b">
                                        <th class="whitespace-nowrap dark:text-white px-6 py-4 font-medium text-center text-gray-900"
                                            colspan="8">
                                            Data tidak ditemukan
                                        </th>
                                    </tr>
                                @endif
                                @foreach ($data_ijins as $data_ijin)
                                    <tr
                                        class="dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b">
                                        @if (auth()->user()->hasRole('admin'))
                                            <td class="px-6 py-4">
                                                {{ $data_ijin->karyawan->name }}
                                            </td>
                                        @endif
                                        <td class="px-6 py-4">
                                            {{ strtoupper($data_ijin->tipe_ijin) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $data_ijin->from_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $data_ijin->to_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $data_ijin->total_hari }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $data_ijin->keterangan }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($data_ijin->is_approved === null)
                                                ⌛
                                            @else
                                                {{ $data_ijin->is_approved ? '✅' : '❌' }}
                                            @endif
                                            @if ($data_ijin->is_approved)
                                                <br />
                                                {{ $data_ijin->approved_at->format('d M Y') }}
                                            @endif
                                        </td>
                                        @if (auth()->user()->hasRole('admin'))
                                            <td class="px-6 py-4 text-center">
                                                @if ($data_ijin->lampiran)
                                                    <a href="{{ route('data-ijin.download', $data_ijin->id) }}"
                                                        class="focus:outline-none hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 me-2 dark:focus:ring-yellow-900 px-3 py-2 mb-2 ml-2 text-sm font-medium text-white bg-yellow-500 rounded-lg"
                                                        target="_blank">
                                                        <i class="fa-solid fa-download"></i>
                                                    </a>
                                                @endif
                                                @if (auth()->user()->hasRole('admin'))
                                                    @if ($data_ijin->is_approved === null)
                                                        <button type="button"
                                                            onclick="changeStatus('{{ $data_ijin->id }}', 'approve')"
                                                            class="hover:text-white hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 me-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800 px-3 py-2 mb-2 text-sm font-medium text-center text-green-700 border border-green-700 rounded-lg">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button"
                                                            onclick="changeStatus('{{ $data_ijin->id }}', 'reject')"
                                                            class="hover:text-white hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 me-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 px-3 py-2 mb-2 text-sm font-medium text-center text-red-700 border border-red-700 rounded-lg">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                @endif

                                                @if ($data_ijin->is_approved === null)
                                                    <form id="delete-form-{{ $data_ijin->id }}"
                                                        action="{{ route('data-ijin.destroy', $data_ijin) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <button type="button"
                                                        onclick="askDelete('delete-form-{{ $data_ijin->id }}')"
                                                        class="focus:outline-none hover:bg-red-800 focus:ring-4 focus:ring-red-300 me-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900 px-3 py-2 mb-2 text-sm font-medium text-white bg-red-700 rounded-lg">
                                                        <i class="fa-solid fa-trash me-2"></i>
                                                        Delete
                                                    </button>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $data_ijins->links() }}
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

            function changeStatus(id, tipe) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Anda akan ${tipe} data ijin ini!`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('data-ijin.approve-reject') }}`,
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                id: id,
                                tipe: tipe,
                            },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Mohon tunggu...',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    showConfirmButton: false,
                                    willOpen: () => {
                                        Swal.showLoading();
                                    },
                                });
                            },
                            success: function(response) {
                                console.log(response.success);
                                if (response.success) {
                                    Swal.fire(
                                        'Berhasil!',
                                        response.message,
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Gagal!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan.',
                                    'error'
                                );
                            }
                        });
                    }
                });

            }
        </script>
    @endpush
</x-app-layout>
