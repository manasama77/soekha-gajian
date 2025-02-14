<x-guest-layout>

    <div id="card" class="max-w-4xl" x-data="{ state: false, focus: false, handShow: false, emailFocus: false, passwordFocus: false }">

        <div class="relative w-full h-full transition-all duration-500 ease-in-out" x-on:mouseover="state=true"
            x-on:mouseleave="state=false">
            <img id="panda" src="{{ Vite::asset('resources/img/panda.png') }}" alt="panda"
                class="left-1/2 -z-0 absolute max-w-xs transition-all duration-200 ease-in-out -translate-y-1/2"
                :class="{
                    'top-0 -translate-x-1/2': (state || focus || emailFocus),
                    'top-1/2 -translate-x-1/2': !(state || focus || emailFocus),
                    '-translate-x-[15%]': (focus),
                    '-translate-x-1/2': !(focus)
                }">
            <div class="min-h-6 left-1/2 relative z-0 max-w-xs transition-all duration-200 ease-in-out"
                :class="{
                    '-top-8 ': (state || focus || emailFocus),
                    'top-20 -translate-x-1/2': !(state || focus || emailFocus),
                    '-translate-x-[15%]': (focus),
                    '-translate-x-1/2':
                        !(focus)
                }">
                <img id="panda_eye_left" src="{{ Vite::asset('resources/img/eye_ball.png') }}" alt="panda eye left"
                    class="max-h-6 left-[32%]  absolute   open-eye">
                <img id="panda_eye_left_close" src="{{ Vite::asset('resources/img/eye_close.png') }}"
                    alt="panda eye left close"
                    class="max-h-[0.35rem] left-[32%] top-[0.4rem]  absolute   closed-eye opacity-0">
                <img id="panda_eye_right" src="{{ Vite::asset('resources/img/eye_ball.png') }}" alt="panda eye right"
                    class="max-h-6 right-[32%]  absolute   open-eye">
                <img id="panda_eye_right_close" src="{{ Vite::asset('resources/img/eye_close.png') }}"
                    alt="panda eye left close"
                    class="max-h-[0.35rem] right-[32%] top-[0.4rem]  absolute   closed-eye opacity-0">
            </div>
            <img id="left_hand_pre" src="{{ Vite::asset('resources/img/left_hand_pre.png') }}" alt="Pre Left Hand"
                class="max-h-[4.5rem] top-[6.8rem] absolute z-0 opacity-0"
                :class="{ 'left_hand_pre_anim': emailFocus, 'left_hand_pre_anim top-[12.6rem]': passwordFocus, 'left_hand_pre_anim top-[12.6rem]': passwordFocus }">

            <main
                class="sm:justify-center left-1/2 top-0 flex flex-col items-center flex-1 min-w-[30vw] px-4 pt-6 z-50 sticky">
                {{-- <div>
                <a href="/">
                    <x-application-logo class="w-40 h-auto fill-current" />
                </a>
            </div> --}}
                <p x-text="handShow ? 'Hand is visible' : 'Hand is not visible'"></p>
                <div class=" dark:bg-dark-eval-1 w-full px-6 py-6 my-0 bg-white rounded-md shadow-md">
                    <div class="relative border border-gray-400">
                        <img id="left_hand" src="{{ Vite::asset('resources/img/left_hand.png') }}" alt="Left Hand"
                            class="absolute z-[9999] max-h-[4.5rem] opacity-0"
                            :class="{
                                'left_hand_anim': (emailFocus || passwordFocus),
                                'left_hand_anim_out': !(emailFocus || passwordFocus),
                                'top-[6.6rem]': (passwordFocus),
                                'top-3': !(passwordFocus)
                            }">
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <!-- Validation Errors -->
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="grid gap-6">
                            <!-- Email Address -->
                            <div class="space-y-2">
                                <x-form.label for="email" :value="__('Email')" />

                                <x-form.input-with-icon-wrapper>
                                    <x-slot name="icon">
                                        <x-heroicon-o-mail aria-hidden="true" class="w-5 h-5" />
                                    </x-slot>

                                    <x-form.input withicon id="email" class="block w-full" type="email"
                                        name="email" :value="old('email')" placeholder="{{ __('Email') }}" required
                                        x-on:focus="focus=true; emailFocus=true; showHand = document.getElementById('left_hand')?.classList.contains('opacity-1') || false; console.log(document.getElementById('left_hand')?.classList.contains('left_hand_anim'))"
                                        x-on:blur="focus=false; emailFocus=false" />
                                </x-form.input-with-icon-wrapper>
                            </div>

                            <!-- Password -->
                            <div class="space-y-2">
                                <x-form.label for="password" :value="__('Password')" />

                                <x-form.input-with-icon-wrapper>
                                    <x-slot name="icon">
                                        <x-heroicon-o-lock-closed aria-hidden="true" class="w-5 h-5" />
                                    </x-slot>

                                    <input type="password" id="prevent_autofill" autocomplete="off" style="display:none"
                                        tabindex="-1" />

                                    <x-form.input withicon id="password" class="block w-full" type="password"
                                        name="password" placeholder="{{ __('Password') }}" required
                                        x-on:focus="focus=true; passwordFocus=true"
                                        x-on:blur="focus=false; passwordFocus=false" autocomplete="off" />
                                </x-form.input-with-icon-wrapper>
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center justify-between">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox"
                                        class="focus:border-purple-300 focus:ring focus:ring-purple-500 dark:border-gray-600 dark:bg-dark-eval-1 dark:focus:ring-offset-dark-eval-1 text-purple-500 border-gray-300 rounded"
                                        name="remember" required x-on:focus="focus=true" x-on:blur="focus=false">

                                    <span class="dark:text-gray-400 ml-2 text-sm text-gray-600">
                                        {{ __('Remember me') }}
                                    </span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a class="hover:underline text-sm text-blue-500"
                                        href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif
                            </div>

                            <div>
                                <x-button class="group-hover:bg-red-500 justify-center w-full gap-2">
                                    <x-heroicon-o-login class="w-6 h-6" aria-hidden="true" />

                                    <span>{{ __('Log in') }}</span>
                                </x-button>
                            </div>

                            @if (Route::has('register'))
                                <p class="dark:text-gray-400 text-sm text-gray-600">
                                    {{ __('Don’t have an account?') }}
                                    <a href="{{ route('register') }}" class="hover:underline text-blue-500">
                                        {{ __('Register') }}
                                    </a>
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </main>

        </div>

    </div>

    @push('scripts')
        <script>
            const inputs = document.querySelectorAll('input');

            const handleFocus = () => {
                focused = true;
            }

            const handleBlur = () => {
                focused = false;
            }

            inputs.forEach(input => {
                input.addEventListener('focus', handleFocus);
                input.addEventListener('blur', handleBlur);
            });


            document.addEventListener('DOMContentLoaded', () => {
                const closedEyes = document.querySelectorAll('.closed-eye');
                const openEyes = document.querySelectorAll('.open-eye');

                const blink = () => {
                    closedEyes.forEach(eye => eye.classList.toggle('opacity-100'));
                    openEyes.forEach(eye => eye.classList.toggle('opacity-0'));
                };

                const randomBlink = () => {
                    blink();
                    setTimeout(() => {
                        blink();
                        setTimeout(randomBlink, Math.random() * 5000 + 1000);
                    }, 200);
                };

                randomBlink();
            });
        </script>
    @endpush
</x-guest-layout>
