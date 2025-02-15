<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <div class="">
                <a href="{{ route('setup.work-days.index') }}"
                    class="py-2.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Kembali
                </a>
            </div>

            <h2 class="dark:text-gray-200 text-xl font-semibold leading-tight text-gray-800">
                {{ __('Tambah Work Day') }}
            </h2>
        </div>
    </x-slot>

    <form action="#" method="POST">
        <div class="py-12">
            <div class="max-w-7xl sm:px-6 lg:px-8 mx-auto mb-10">
                <div
                    class="dark:bg-gray-800 dark:border-gray-700 block max-w-sm p-6 mx-auto bg-white border border-gray-200 rounded-lg shadow">

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
                    <div class="mb-4">
                        <label for="periode_cutoff_id"
                            class="dark:text-white block mb-2 text-sm font-medium text-gray-900">
                            Periode Cutoff
                        </label>
                        <select id="periode_cutoff_id" name="periode_cutoff_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required>
                            <option value="" selected>-Pilih Periode Cutoff-</option>
                            @foreach ($periode_cutoffs as $periode_cutoff)
                                <option value="{{ $periode_cutoff->id }}">
                                    {{ $periode_cutoff->start_date->format('d F Y') }} s/d
                                    {{ $periode_cutoff->end_date->format('d F Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('periode_cutoff_id')
                            <p class="text-xs italic text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            <div class="max-w-xl mx-auto mb-10">
                <div
                    class="dark:bg-gray-800 dark:border-gray-700 block max-w-full p-6 mx-auto bg-white border border-gray-200 rounded-lg shadow">


                    <div class="relative overflow-x-auto">
                        <table class="rtl:text-right dark:text-gray-400 w-full text-sm text-left text-gray-500">
                            <thead
                                class="bg-gray-50 dark:bg-gray-700 dark:text-gray-400 text-xs text-gray-700 uppercase">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Tanggal
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Shift
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="v_periode"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="max-w-2xl px-6 mx-auto">
                <button type="submit"
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 w-full">
                    Simpan
                </button>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            let shifts = @json($shifts);

            $(document).ready(function() {
                $('#periode_cutoff_id').on('change', function() {
                    var periodeCutoffId = $(this).val();
                    generatePeriodeCutoff(periodeCutoffId);
                });

                $('form').on('submit', (e) => {
                    e.preventDefault();
                    prosesStore();
                })
            })

            function generatePeriodeCutoff(periode_cutoff_id) {
                $.ajax({
                    url: `/setup/work-days/generate_period/${periode_cutoff_id}`,
                    type: 'GET',
                    dataType: 'json',
                    beforeSend: function() {
                        $('#v_periode').html('');
                        $.blockUI();
                    }
                }).always(() => {
                    $.unblockUI();
                }).fail(e => {
                    // if response header 404
                    if (e.status === 404) {
                        $('#v_periode').html('');
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: e.message,
                            icon: 'error',
                        });
                    }
                }).done(e => {
                    let periode = e.data;
                    console.log(periode);
                    let template = '';

                    for (let i = 0; i < periode.length; i++) {
                        let tanggal = periode[i];
                        template += `
                            <tr class="dark:bg-gray-800 dark:border-gray-700 bg-white border-b border-gray-200">
                                <td class="px-6 py-4">
                                    <div>
                                        <label for="tanggal">${tanggal}</label>
                                        <input type="hidden" name="tanggal[]"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            placeholder="Tanggal" value="${tanggal}" required readonly />
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <select name="shift_id[]"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        required>
                                        <option value="">-Pilih Shift-</option>
                                        ${shifts.map(shift => {
                                            return `<option value="${shift.id}">${shift.name}</option>`
                                        }).join('')}
                                    </select>
                                </td>
                            </tr>
                        `;
                    }

                    console.log(template);
                    $('#v_periode').html(template);
                });
            }

            function prosesStore() {
                let data = $('form').serialize();
                $.ajax({
                    url: `{{ route('setup.work-days.store') }}`,
                    type: 'POST',
                    data: data,
                    dataType: 'json',
                    beforeSend: function() {
                        $.blockUI();
                    }
                }).always(() => {
                    $.unblockUI();
                }).fail(e => {
                    let message = e.responseJSON.message;
                    // get header status code
                    if (e.status === 422) {
                        Swal.fire({
                            title: 'Error!',
                            html: message,
                            icon: 'error',
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: message,
                            icon: 'error',
                        });
                    }
                }).done(e => {
                    Swal.fire({
                        title: 'Success!',
                        text: e.message,
                        icon: 'success',
                    }).then(() => {
                        location.reload();
                    })
                })
            }
        </script>
    @endpush
</x-app-layout>
