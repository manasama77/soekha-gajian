<x-app-layout>
    <x-slot name="header">
        <h2 class="dark:text-gray-200 text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex items-center justify-center h-full">
        <div class="min-w-80 p-4 text-yellow-700 bg-yellow-100 border-l-4 border-yellow-500" role="alert">
            <p class="font-bold">Notification</p>
            <p>Belum ada periode cutoff.</p>
        </div>
    </div>


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    @endpush
</x-app-layout>
