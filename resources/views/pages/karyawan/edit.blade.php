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
                {{ __('Edit Karyawan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl sm:px-6 lg:px-8 mx-auto">
            <div
                class="dark:bg-gray-800 dark:border-gray-700 block max-w-sm p-6 mx-auto bg-white border border-gray-200 rounded-lg shadow">
                <form action="{{ route('setup.karyawan.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="email"
                            class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Email</label>
                        <input type="email" name="email" id="email" placeholder="Masukkan Email untuk Login"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('email') border-red-500 @enderror"
                            value="{{ $user->email }}" required />
                        @error('email')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <hr class="py-4" />

                    <div class="mb-4">
                        <label for="departement_id"
                            class="dark:text-white block mb-2 text-sm font-medium text-gray-900">
                            Departement
                        </label>
                        <select id="departement_id" name="departement_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required>
                            @foreach ($departements as $departement)
                                <option @selected($departement->id === $user->departement_id) value="{{ $departement->id }}">
                                    {{ $departement->name }}</option>
                            @endforeach
                        </select>
                        @error('departement_id')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="name" class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Nama
                            Karyawan</label>
                        <input type="text" name="name" id="name" placeholder="Masukkan Nama Karyawan"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('name') border-red-500 @enderror"
                            value="{{ $user->name }}" required />
                        @error('name')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="tipe_gaji" class="dark:text-white block mb-2 text-sm font-medium text-gray-900">
                            Tipe Gaji
                        </label>
                        <select id="tipe_gaji" name="tipe_gaji"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required>
                            <option @selected($user->tipe_gaji == 'bulanan') value="bulanan">Bulanan</option>
                            <option @selected($user->tipe_gaji == 'harian') value="harian">Harian</option>
                        </select>
                        @error('tipe_gaji')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="gaji_bulanan_group" class="mb-4">
                        <label for="gaji_pokok"
                            class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Gaji Pokok</label>
                        <input type="number" name="gaji_pokok" id="gaji_pokok" placeholder="Masukkan Gaji Pokok"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('gaji_pokok') border-red-500 @enderror"
                            value="{{ $user->gaji_pokok }}" required />
                        @error('gaji_pokok')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="gaji_harian_group" class="mb-4">
                        <label for="gaji_harian"
                            class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Gaji Harian</label>
                        <input type="number" name="gaji_harian" id="gaji_harian" placeholder="Masukkan Gaji Pokok"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('gaji_harian') border-red-500 @enderror"
                            value="{{ $user->gaji_harian }}" required />
                        @error('gaji_harian')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="join_date" class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Join
                            Date</label>
                        <input type="date" name="join_date" id="join_date"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('join_date') border-red-500 @enderror"
                            value="{{ $user->join_date->format('Y-m-d') }}" required />
                        @error('join_date')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="total_cuti"
                            class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Total Cuti</label>
                        <input type="number" name="total_cuti" id="total_cuti" placeholder="Masukkan Total Cuti"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('total_cuti') border-red-500 @enderror"
                            value="{{ $user->total_cuti }}" />
                        @error('total_cuti')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="sisa_cuti" class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Sisa
                            Cuti</label>
                        <input type="number" name="sisa_cuti" id="sisa_cuti" placeholder="Masukkan Sisas Cuti"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('sisa_cuti') border-red-500 @enderror"
                            value="{{ $user->sisa_cuti }}" />
                        @error('sisa_cuti')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="whatsapp"
                            class="dark:text-white block mb-2 text-sm font-medium text-gray-900">Whatsapp</label>
                        <input type="tel" name="whatsapp" id="whatsapp" placeholder="Masukkan No Whatsapp"
                            class="bg-gray-50 border text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('whatsapp') border-red-500 @enderror"
                            value="{{ $user->whatsapp }}" required />
                        @error('whatsapp')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="is_admin" name="is_admin" value="1"
                                class="peer sr-only"
                                {{ $user->getRoleNames()->first() == 'Admin' ? 'checked' : '' }} />
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
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
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

                if (`{{ $user->tipe_gaji }}` == 'bulanan') {
                    $('#gaji_bulanan_group').show();
                    $('#gaji_harian_group').hide();
                } else {
                    $('#gaji_bulanan_group').hide();
                    $('#gaji_harian_group').show();
                }
            });
        </script>
    @endpush
</x-app-layout>
