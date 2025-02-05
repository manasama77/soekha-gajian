<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <div class="">
                <a href="{{ route('data-kehadiran.index') }}"
                    class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Kembali
                </a>
            </div>

            <h2 class="dark:text-gray-200 text-xl font-semibold leading-tight text-gray-800">
                {{ __('Tambah Data Presensi') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl sm:px-6 lg:px-8 mx-auto">
            <div
                class="dark:bg-gray-800 dark:border-gray-700 block max-w-sm p-6 mx-auto bg-white border border-gray-200 rounded-lg shadow">

                @if ($errors->any())
                    <div class="relative px-4 py-3 mt-4 text-red-700 bg-red-100 border border-red-400 rounded"
                        role="alert">
                        <strong class="font-bold">Error!</strong>
                        @if ($errors->count() > 1)
                            <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="sm:inline block">{{ $errors->first() }}</span>
                        @endif
                    </div>
                @endif

                <form action="{{ route('data-kehadiran.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if (auth()->user()->hasRole('admin'))
                        <div class="mb-4">
                            <label for="periode_cutoff_id"
                                class="dark:text-white block mb-2 text-sm font-medium text-gray-900">
                                Periode Cutoff
                            </label>
                            <select id="periode_cutoff_id" name="periode_cutoff_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                                <option value="{{ $periode_cutoff->id }}">
                                    {{ $periode_cutoff->start_date->format('d F Y') }} s/d
                                    {{ $periode_cutoff->end_date->format('d F Y') }}
                                </option>
                            </select>
                            @error('periode_cutoff_id')
                                <p class="text-xs italic text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="user_id" class="dark:text-white block mb-2 text-sm font-medium text-gray-900">
                                Karyawan
                            </label>
                            <select id="user_id" name="user_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-xs italic text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    @php
                        $hidden = null;
                        if (auth()->user()->hasRole('karyawan')) {
                            $hidden = 'hidden';
                        }
                    @endphp
                    <div class="mb-4 {{ $hidden }}">
                        <label for="tipe_kehadiran"
                            class="dark:text-white block mb-2 text-sm font-medium text-gray-900">
                            Tipe Kehadiran
                        </label>
                        <select id="tipe_kehadiran" name="tipe_kehadiran"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required>
                            @if (auth()->user()->hasRole('karyawan'))
                                @if ($default_tipe_kehadiran == 'in')
                                    <option value="in">Clock In</option>
                                @else
                                    <option value="out">Clock Out</option>
                                @endif
                            @else
                                <option value="in">Clock In</option>
                                <option value="out">Clock Out</option>
                            @endif
                        </select>
                        @error('tipe_kehadiran')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="foto"
                            class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Foto</label>

                        <input type="file" name="foto" id="foto"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('foto') border-red-500 @enderror"
                            capture="camera" required />

                        <div id="results"
                            class="min-h-36 dark:text-white h-auto max-w-lg text-black border rounded-lg">
                        </div>

                        @error('foto')
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

    @push('scripts')
        <script>
            const foto = document.getElementById('foto');
            const results = document.getElementById('results');

            foto.addEventListener('change', function() {
                const file = this.files[0];

                if (file) {
                    const reader = new FileReader();

                    reader.onload = function() {
                        const img = document.createElement('img');
                        img.src = reader.result;
                        img.className = 'w-full h-full object-cover';
                        results.innerHTML = '';
                        results.appendChild(img);
                    }

                    reader.readAsDataURL(file);
                }
            });
        </script>
    @endpush
</x-app-layout>
