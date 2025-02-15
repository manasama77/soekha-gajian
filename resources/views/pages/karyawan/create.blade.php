<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <div class="">
                <a href="{{ route('setup.karyawan.index') }}"
                    class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Kembali
                </a>
            </div>

            <h2 class="dark:text-gray-200 text-xl font-semibold leading-tight text-gray-800">
                {{ __('Tambah Karyawan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl sm:px-6 lg:px-8 mx-auto">

            @if ($errors->any())
                <div class="relative px-4 py-3 mt-4 mb-4 text-red-700 bg-red-100 border border-red-400 rounded"
                    role="alert">
                    <strong class="font-bold">Error!</strong>
                    <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('setup.karyawan.store') }}" method="POST">
                @csrf
                <div class="md:flex-nowrap flex flex-wrap justify-between gap-10">
                    <div class="md:flex-1 flex-auto">
                        <div
                            class="dark:bg-gray-800 dark:border-gray-700 block w-full p-6 mx-auto bg-white border border-gray-200 rounded-lg shadow">

                            <div class="mb-4">
                                <label for="email"
                                    class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Email</label>
                                <input type="email" name="email" id="email"
                                    placeholder="Masukkan Email untuk Login"
                                    class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('email') border-red-500 @enderror"
                                    value="{{ old('email') }}" required />
                                @error('email')
                                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="password"
                                    class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Password</label>
                                <input type="password" name="password" id="password" placeholder="Masukkan Password"
                                    class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('password') border-red-500 @enderror"
                                    value="{{ old('password') }}" required />
                                @error('password')
                                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation"
                                    class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    placeholder="Konfirmasi Password"
                                    class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('password_confirmation') border-red-500 @enderror"
                                    value="{{ old('password_confirmation') }}" required />
                                @error('password_confirmation')
                                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="md:flex-1 flex-auto">
                        <div
                            class="dark:bg-gray-800 dark:border-gray-700 block w-full p-6 mx-auto bg-white border border-gray-200 rounded-lg shadow">

                            <div class="mb-4">
                                <label for="departement_id"
                                    class="dark:text-white block mb-2 text-sm font-medium text-gray-900">
                                    Departement
                                </label>
                                <select id="departement_id" name="departement_id"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    required>
                                    @foreach ($departements as $departement)
                                        <option value="{{ $departement->id }}">{{ $departement->name }}</option>
                                    @endforeach
                                </select>
                                @error('departement_id')
                                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="name"
                                    class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Nama
                                    Karyawan</label>
                                <input type="text" name="name" id="name" placeholder="Masukkan Nama Karyawan"
                                    class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('name') border-red-500 @enderror"
                                    value="{{ old('name') }}" required />
                                @error('name')
                                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="tipe_gaji"
                                    class="dark:text-white block mb-2 text-sm font-medium text-gray-900">
                                    Tipe Gaji
                                </label>
                                <select id="tipe_gaji" name="tipe_gaji"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    required>
                                    <option @selected(old('tipe_gaji') == 'bulanan') value="bulanan">Bulanan</option>
                                    <option @selected(old('tipe_gaji') == 'harian') value="harian">Harian</option>
                                </select>
                                @error('tipe_gaji')
                                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div id="gaji_bulanan_group" class="mb-4">
                                <label for="gaji_pokok"
                                    class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Gaji
                                    Pokok</label>
                                <input type="number" name="gaji_pokok" id="gaji_pokok"
                                    placeholder="Masukkan Gaji Pokok"
                                    class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('gaji_pokok') border-red-500 @enderror"
                                    value="{{ old('gaji_pokok') ?? 0 }}" required />
                                @error('gaji_pokok')
                                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div id="gaji_harian_group" class="mb-4">
                                <label for="gaji_harian"
                                    class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Gaji
                                    Harian</label>
                                <input type="number" name="gaji_harian" id="gaji_harian"
                                    placeholder="Masukkan Gaji Harian"
                                    class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('gaji_harian') border-red-500 @enderror"
                                    value="{{ old('gaji_harian') ?? 0 }}" required />
                                @error('gaji_harian')
                                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="gaji_perbantuan_shift"
                                    class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Gaji
                                    Perbantuan Shift</label>
                                <input type="number" name="gaji_perbantuan_shift" id="gaji_perbantuan_shift"
                                    placeholder="Masukkan Gaji Harian"
                                    class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('gaji_perbantuan_shift') border-red-500 @enderror"
                                    value="{{ old('gaji_perbantuan_shift') ?? 0 }}" required />
                                @error('gaji_perbantuan_shift')
                                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="join_date"
                                    class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Join
                                    Date</label>
                                <input type="date" name="join_date" id="join_date"
                                    class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('join_date') border-red-500 @enderror"
                                    value="{{ old('join_date') }}" required />
                                @error('join_date')
                                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="total_cuti"
                                    class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Total
                                    Cuti</label>
                                <input type="number" name="total_cuti" id="total_cuti"
                                    placeholder="Masukkan Total Cuti"
                                    class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('total_cuti') border-red-500 @enderror"
                                    value="{{ old('total_cuti') }}" />
                                @error('total_cuti')
                                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="sisa_cuti"
                                    class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Sisa
                                    Cuti</label>
                                <input type="number" name="sisa_cuti" id="sisa_cuti"
                                    placeholder="Masukkan Sisa Cuti"
                                    class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('sisa_cuti') border-red-500 @enderror"
                                    value="{{ old('sisa_cuti') }}" />
                                @error('sisa_cuti')
                                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="whatsapp"
                                    class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Whatsapp</label>
                                <input type="tel" name="whatsapp" id="whatsapp"
                                    placeholder="Masukkan No Whatsapp"
                                    class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('whatsapp') border-red-500 @enderror"
                                    value="{{ old('whatsapp') }}" required />
                                @error('whatsapp')
                                    <p class="text-xs italic text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="is_admin" name="is_admin" value="1"
                                        class="peer sr-only">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600">
                                    </div>
                                    <span class="ms-3 dark:text-gray-300 text-sm font-medium text-gray-900">Berikan
                                        role Admin</span>
                                </label>
                            </div>

                            <div class="flex items-center justify-end">
                                <button type="submit"
                                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                    Simpan
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>


        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#gaji_harian_group').hide();

                $('#tipe_gaji').on('change', function() {
                    $('#gaji_pokok').val(0);
                    $('#gaji_harian').val(0);
                    if ($('#tipe_gaji').val() == 'bulanan') {
                        $('#gaji_bulanan_group').show();
                        $('#gaji_harian_group').hide();
                    } else if ($('#tipe_gaji').val() == 'harian') {
                        $('#gaji_bulanan_group').hide();
                        $('#gaji_harian_group').show();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
