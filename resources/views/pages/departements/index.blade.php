<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="dark:text-gray-200 text-xl font-semibold leading-tight text-gray-800">
                {{ __('Departements') }}
            </h2>

            <div class="">
                <a href="{{ route('setup.departements.create') }}"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Tambah Departement
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="md:max-w-2xl sm:px-6 lg:px-8 max-w-full mx-auto">
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

                    <div class="flex items-center justify-end mb-4">
                        <form action="{{ route('setup.departements.index') }}" method="GET"
                            class="md:max-w-sm w-full">
                            <div class="flex">
                                <input type="text" name="search" placeholder="Cari Departement"
                                    class="dark:bg-gray-700 dark:text-gray-300 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full px-4 py-2 border border-gray-300 rounded-lg"
                                    value="{{ $search }}" required />
                                <button type="submit"
                                    class="ml-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Cari
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="sm:rounded-lg relative overflow-x-auto shadow-md">
                        <table id="tables" class="dark:text-gray-400 w-full text-sm text-left text-gray-500">
                            <thead
                                class="bg-gray-50 dark:bg-gray-700 dark:text-gray-400 text-xs text-gray-700 uppercase">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center">
                                        <i class="fas fa-cogs"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($departements->isEmpty())
                                    <tr
                                        class="dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b">
                                        <th class="whitespace-nowrap dark:text-white px-6 py-4 font-medium text-center text-gray-900"
                                            colspan="2">
                                            Data tidak ditemukan
                                        </th>
                                    </tr>
                                @endif
                                @foreach ($departements as $departement)
                                    <tr
                                        class="dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b">
                                        <td class="px-6 py-4">
                                            {{ $departement->name }}
                                        </td>
                                        <td class="px-6 py-4 text-center">

                                            <div class="flex flex-wrap justify-center gap-3">
                                                <a href="{{ route('setup.departements.edit', $departement) }}"
                                                    class="focus:outline-none text-white bg-yellow-500 hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:focus:ring-yellow-900">
                                                    <i class="fa-solid fa-pencil me-2"></i>
                                                    Edit
                                                </a>

                                                <form id="delete-form-{{ $departement->id }}"
                                                    action="{{ route('setup.departements.destroy', $departement) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button"
                                                    onclick="askDelete('delete-form-{{ $departement->id }}')"
                                                    class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                                    <i class="fa-solid fa-trash me-2"></i>
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $departements->links() }}
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
