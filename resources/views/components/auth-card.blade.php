<main class="sm:justify-center flex flex-col items-center flex-1 px-4 pt-6">
    <div>
        <a href="/">
            <x-application-logo class="w-40 h-auto fill-current" />
        </a>
    </div>

    <div class="sm:max-w-md dark:bg-dark-eval-1 w-full px-6 py-4 my-6 overflow-hidden bg-white rounded-md shadow-md">
        {{ $slot }}
    </div>
</main>
