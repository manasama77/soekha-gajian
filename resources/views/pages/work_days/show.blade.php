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
                {{ __('Detail Work Day') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="md:max-w-5xl sm:px-6 lg:px-8 max-w-full mx-auto">
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

                    <div class="mb-3 space-y-3">
                        <h2 class="dark:text-gray-200 text-base font-semibold leading-tight text-gray-800">
                            <span class="font-light">Karyawan:</span> {{ $users->name }}
                        </h2>

                        <h2 class="dark:text-gray-200 text-base font-semibold leading-tight text-gray-800">
                            <span class="font-light">Periode Cutoff:</span>
                            {{ $periode_cutoffs->start_date->format('d F Y') }} -
                            {{ $periode_cutoffs->end_date->format('d F Y') }}
                        </h2>
                    </div>

                    <div class="sm:rounded-lg relative overflow-x-auto shadow-md">
                        <table id="tables" class="dark:text-gray-400 w-full text-sm text-left text-gray-500">
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
                            <tbody>
                                @if ($work_days->isEmpty())
                                    <tr
                                        class="dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b">
                                        <th class="whitespace-nowrap dark:text-white px-6 py-4 font-medium text-center text-gray-900"
                                            colspan="2">
                                            Data tidak ditemukan
                                        </th>
                                    </tr>
                                @endif
                                @foreach ($work_days as $work_day)
                                    <tr
                                        class="dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 bg-white border-b">
                                        <td class="px-6 py-4">
                                            {{ \Carbon\Carbon::parse($work_day->tanggal)->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $work_day->shift->name }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


</x-app-layout>
