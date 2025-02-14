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
            <p x-text="handShow"></p>
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
                {{ $slot }}
            </div>
        </main>

    </div>

</div>
