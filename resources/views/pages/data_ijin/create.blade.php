<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <div class="">
                <a href="{{ route('data-ijin.index') }}"
                    class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Kembali
                </a>
            </div>

            <h2 class="dark:text-gray-200 text-xl font-semibold leading-tight text-gray-800">
                {{ __('Tambah Data Ijin') }}
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
                        <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('data-ijin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if (auth()->user()->hasRole('admin'))
                        <div class="mb-4">
                            <label for="user_id" class="dark:text-white block mb-2 text-sm font-medium text-gray-900">
                                Karyawan
                            </label>
                            <select id="user_id" name="user_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                                @foreach ($users as $user)
                                    <option @selected(old('user_id') == $user->id) value="{{ $user->id }}">
                                        {{ $user->name }} - {{ $user->sisa_cuti }} hari
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="text-xs italic text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="tipe_ijin" class="dark:text-white block mb-2 text-sm font-medium text-gray-900">
                            Tipe Ijin
                        </label>
                        <select id="tipe_ijin" name="tipe_ijin"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required>
                            <option @selected(old('tipe_ijin') == 'cuti') value="cuti">Cuti</option>
                            <option @selected(old('tipe_ijin') == 'sakit dengan surat dokter') value="sakit dengan surat dokter">Sakit Dengan Surat
                                Dokter</option>
                            <option @selected(old('tipe_ijin') == 'ijin potong gaji') value="ijin potong gaji">Ijin Potong Gaji</option>
                        </select>

                        @if (auth()->user()->hasRole('karyawan'))
                            <p id="v_sisa_cuti" class="dark:text-gray-300 mt-1 text-sm text-gray-500">
                                Sisa Cuti: <span id="sisa_cuti">{{ auth()->user()->sisa_cuti }}</span> hari
                            </p>
                        @endif

                        @error('tipe_ijin')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="from_date" class="dark:text-white block mb-2 text-sm font-medium text-gray-900">
                            From Date <sup class="text-red-500">*</sup>
                        </label>

                        <input type="date" name="from_date" id="from_date" value="{{ old('from_date') }}"
                            min="{{ $min_date }}" max="{{ $max_date }}"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('from_date') border-red-500 @enderror"
                            required />

                        @error('from_date')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="to_date" class="dark:text-white block mb-2 text-sm font-medium text-gray-900">
                            To Date <sup class="text-red-500">*</sup>
                        </label>

                        <input type="date" name="to_date" id="to_date" value="{{ old('to_date') }}"
                            min="{{ $min_date }}" max="{{ $max_date }}"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('to_date') border-red-500 @enderror"
                            required />

                        @error('to_date')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="keterangan" class="dark:text-white block mb-2 text-sm font-medium text-gray-900">
                            Keterangan <sup class="text-red-500">*</sup>
                        </label>

                        <input type="text" name="keterangan" id="keterangan" value="{{ old('keterangan') }}"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('keterangan') border-red-500 @enderror"
                            required />

                        @error('keterangan')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="lampiran" class="dark:text-white block mb-2 text-sm font-medium text-gray-900">
                            Lampiran <sup id="lampiran_required" class="text-red-500">
                                {{ in_array(old('tipe_ijin'), ['cuti', 'sakit dengan surat dokter']) ? '*' : '' }}
                            </sup>
                        </label>

                        <input type="file" name="lampiran" id="lampiran"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('name') border-red-500 @enderror" />

                        <p id="helper-text-explanation" class="dark:text-gray-400 mt-2 text-xs text-gray-500">
                            Khusus Sakit Dengan Surat Dokter, lampiran harus diisi.
                        </p>

                        @error('lampiran')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit"
                            class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 w-full">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $('#tipe_ijin').on('change', () => {
                const tipe_ijin = $('#tipe_ijin').val();
                $('#lampiran').attr('required', false);
                $('#lampiran_required').text('');
                $('#v_sisa_cuti').hide();

                if (tipe_ijin === 'sakit dengan surat dokter') {
                    $('#lampiran').attr('required', true);
                    $('#lampiran_required').text('*');
                } else if (tipe_ijin === 'cuti') {
                    $('#v_sisa_cuti').show();
                }
            })
        </script>
    @endpush
</x-app-layout>
