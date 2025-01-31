<div class="flex items-center justify-between flex-shrink-0 px-3">
    <!-- Logo -->
    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2">
        <x-application-logo aria-hidden="true" class="w-32 h-auto" />

        <span class="sr-only">Dashboard</span>
    </a>

    <!-- Toggle button -->
    <x-button type="button" icon-only sr-text="Toggle sidebar" variant="secondary"
        x-show="isSidebarOpen || isSidebarHovered" x-on:click="isSidebarOpen = !isSidebarOpen">
        <x-icons.menu-fold-right x-show="!isSidebarOpen" aria-hidden="true" class="lg:block hidden w-6 h-6" />

        <x-icons.menu-fold-left x-show="isSidebarOpen" aria-hidden="true" class="lg:block hidden w-6 h-6" />

        <x-heroicon-o-x aria-hidden="true" class="lg:hidden w-6 h-6" />
    </x-button>
</div>
