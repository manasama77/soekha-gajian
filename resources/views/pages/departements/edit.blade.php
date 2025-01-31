<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <div class="">
                <a href="{{ route('setup.departements.index') }}"
                    class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Kembali
                </a>
            </div>

            <h2 class="dark:text-gray-200 text-xl font-semibold leading-tight text-gray-800">
                {{ __('Edit Departement') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl sm:px-6 lg:px-8 mx-auto">
            <div
                class="dark:bg-gray-800 dark:border-gray-700 block max-w-sm p-6 mx-auto bg-white border border-gray-200 rounded-lg shadow">
                <form action="{{ route('setup.departements.update', $departement) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Nama
                            Departement</label>
                        <input type="text" name="name" id="name" placeholder="Masukkan Nama Departement"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('name') border-red-500 @enderror"
                            value="{{ $departement->name }}" required />
                        @error('name')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center justify-end">
                        <button type="submit"
                            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
