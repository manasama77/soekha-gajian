<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="dark:text-gray-200 text-xl font-semibold leading-tight text-gray-800">
                {{ __('Data Request Kehadiran') }}
            </h2>

            <div class="">
                <a href="{{ route('request-kehadiran.create') }}"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Request Kehadiran
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
                    <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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
                                        Karyawan
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Tanggal
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Clock In
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Clock Out
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Terlambat
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Status
                                    </th>
                                    @if (auth()->user()->hasRole('admin'))
                                        <th scope="col" class="px-6 py-3 text-center">
                                            <i class="fas fa-cogs"></i>
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if ($request_kehadirans->isEmpty())
                                    <tr
                                        class="dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b">
                                        <th class="whitespace-nowrap dark:text-white px-6 py-4 font-medium text-center text-gray-900"
                                            colspan="7">
                                            Data tidak ditemukan
                                        </th>
                                    </tr>
                                @endif
                                @foreach ($request_kehadirans as $request_kehadiran)
                                    <tr
                                        class="dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b">
                                        <td class="px-6 py-4">
                                            {{ $request_kehadiran->karyawan->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $request_kehadiran->tanggal->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $request_kehadiran->clock_in }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $request_kehadiran->clock_out }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $request_kehadiran->menit_terlambat }} Menit
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($request_kehadiran->is_approved === null)
                                                ⌛
                                            @else
                                                {{ $request_kehadiran->is_approved ? '✅' : '❌' }}
                                            @endif
                                            @if ($request_kehadiran->is_approved)
                                                <br />
                                                {{ $request_kehadiran->approved_at->format('d M Y H:i:s') }}
                                            @endif
                                        </td>
                                        @if (auth()->user()->hasRole('admin'))
                                            <td class="px-6 py-4">
                                                @if (auth()->user()->hasRole('admin'))
                                                    <br />
                                                    @if ($request_kehadiran->is_approved === null)
                                                        <button type="button"
                                                            onclick="changeStatus('{{ $request_kehadiran->id }}', 'approve')"
                                                            class="hover:text-white hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 me-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800 px-3 py-2 mb-2 text-sm font-medium text-center text-green-700 border border-green-700 rounded-lg">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button type="button"
                                                            onclick="changeStatus('{{ $request_kehadiran->id }}', 'reject')"
                                                            class="hover:text-white hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 me-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 px-3 py-2 mb-2 text-sm font-medium text-center text-red-700 border border-red-700 rounded-lg">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif

                                                    <form id="delete-form-{{ $request_kehadiran->id }}"
                                                        action="{{ route('request-kehadiran.destroy', $request_kehadiran) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <button type="button"
                                                        onclick="askDelete('delete-form-{{ $request_kehadiran->id }}')"
                                                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
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
                        {{ $request_kehadirans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function changeStatus(id, tipe) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Anda akan ${tipe} data lembur ini!`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ route('request-kehadiran.approve-reject') }}`,
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
