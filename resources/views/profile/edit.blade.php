<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="sm:p-8 sm:rounded-lg dark:bg-gray-800 p-4 bg-white shadow">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="sm:p-8 sm:rounded-lg dark:bg-gray-800 p-4 bg-white shadow">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        {{-- <div class="sm:p-8 sm:rounded-lg dark:bg-gray-800 p-4 bg-white shadow">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div> --}}
    </div>
</x-app-layout>
