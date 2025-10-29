<x-filament-panels::page.simple>
    <div class="flex justify-center min-h-screen items-center bg-gray-50 dark:bg-gray-900">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">

            {{-- Logo --}}
            <div class="text-center mb-6 mt-3">
                <img src="{{ asset('assets/images/download.JPG') }}" 
                     alt="Logo"
                     class="mx-auto h-16 w-16 rounded-full shadow-md">
                <h2 class="mt-6 text-2xl font-bold text-gray-800 dark:text-gray-100">
                    Login Siswa
                </h2>
            </div>

            {{-- Form Login --}}
            <form id="login-form" wire:submit="authenticate" class="space-y-4 my-4">
                {{ $this->form }}

                {{-- Cloudflare Turnstile (Manual Mode) --}}
                <div class="mt-3 flex justify-center">
                    <div 
                        class="cf-turnstile" 
                        data-sitekey="{{ env('TURNSTILE_SITE_KEY') }}" 
                        data-theme="{{ filament()->getTheme() === 'dark' ? 'dark' : 'light' }}"
                        data-language="id"
                        data-mode="manual"
                        data-callback="onCaptchaSuccess"
                        data-expired-callback="onCaptchaExpired"
                        data-error-callback="onCaptchaError"
                    ></div>
                </div>

                {{-- Pesan Error --}}
                <p id="captcha-error" class="text-red-600 text-sm text-center hidden mt-2">
                    ⚠️ Pastikan Anda bukan robot sebelum login.
                </p>

                {{-- Tombol Login --}}
                <div class="mt-4">
                    <button id="login-btn" type="submit" 
                            class="w-full px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                        Login
                    </button>
                </div>
            </form>

            {{-- Script Cloudflare --}}
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

            {{-- Google Login --}}
            <div class="mt-4">
                <a href="{{ route('google.redirect') }}"
                   class="w-full flex justify-center items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                    <x-filament::icon icon="heroicon-o-arrow-right-on-rectangle" class="w-5 h-5" />
                    Login dengan Google
                </a>
            </div>

            {{-- Daftar --}}
            <div class="mt-4 text-center">
                <a href="/siswa.wh/register"
                   class="inline-block w-full px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition text-center">
                    Daftar di sini
                </a>
            </div> 
        </div>
    </div>

    {{-- Script Handle Captcha --}}
    <script>
        let isCaptchaVerified = false;
        const loginBtn = document.getElementById('login-btn');
        const errorMsg = document.getElementById('captcha-error');

        // ✅ Ketika captcha berhasilelcome
        function onCaptchaSuccess(token) {
            isCaptchaVerified = true;
            loginBtn.disabled = false;
            errorMsg.classList.add('hidden');
        }

        // ❌ Jika captcha expired
        function onCaptchaExpired() {
            isCaptchaVerified = false;
            loginBtn.disabled = true;
            errorMsg.classList.remove('hidden');
        }

        // ❌ Jika captcha gagal
        function onCaptchaError() {
            isCaptchaVerified = false;
            loginBtn.disabled = true;
            errorMsg.classList.remove('hidden');
        }

        // 🧩 Cegah login tanpa captcha
        document.getElementById('login-form').addEventListener('submit', function (e) {
            if (!isCaptchaVerified) {
                e.preventDefault();
                errorMsg.classList.remove('hidden');
            }
        });
    </script>
</x-filament-panels::page.simple>
