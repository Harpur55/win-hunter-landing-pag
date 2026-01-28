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

            {{-- Form --}}
            <form wire:submit="authenticate" class="space-y-4 my-4">
                {{ $this->form }}

                {{-- Token captcha (WAJIB untuk server) --}}
                <input type="hidden" name="cf-turnstile-response" id="cf-turnstile-response">

                {{-- Cloudflare Turnstile --}}
                <div class="mt-4 flex justify-center" wire:ignore>
                    <div id="turnstile-container"></div>
                </div>

                {{-- Error captcha --}}
                @error('captcha')
                    <p class="text-red-600 text-sm text-center mt-2">
                        {{ $message }}
                    </p>
                @enderror

                {{-- Button --}}
                <div class="mt-4">
                    <button
                        type="submit"
                        class="w-full px-4 py-2 bg-green-600 text-white font-medium rounded-md
                               hover:bg-green-700 focus:ring-2 focus:ring-offset-2
                               focus:ring-green-500 transition">
                        Login
                    </button>
                </div>
            </form>

            {{-- Google Login --}}
            <div class="mt-4">
                <a href="{{ route('google.redirect') }}"
                   class="w-full flex justify-center items-center gap-2 px-4 py-2
                          bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                    <x-filament::icon icon="heroicon-o-arrow-right-on-rectangle" class="w-5 h-5" />
                    Login dengan Google
                </a>
            </div>

            {{-- Register --}}
            <div class="mt-4 text-center">
                <a href="/siswa/register"
                   class="inline-block w-full px-4 py-2 bg-green-600 text-white
                          font-medium rounded-md hover:bg-green-700 transition">
                    Daftar di sini
                </a>
            </div>

        </div>
    </div>

    {{-- Turnstile --}}
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

    <script>
        let widgetId = null;

        function renderTurnstile() {
            if (!window.turnstile) return;

            const container = document.getElementById('turnstile-container');
            if (!container || widgetId !== null) return;

            widgetId = turnstile.render(container, {
                sitekey: "{{ config('services.turnstile.site_key') }}",
                theme: "{{ filament()->getTheme() === 'dark' ? 'dark' : 'light' }}",
                language: "id",
                callback: token => {
                    document.getElementById('cf-turnstile-response').value = token;
                },
                'expired-callback': () => {
                    document.getElementById('cf-turnstile-response').value = '';
                },
                'error-callback': () => {
                    document.getElementById('cf-turnstile-response').value = '';
                },
            });
        }

        document.addEventListener('DOMContentLoaded', renderTurnstile);
        document.addEventListener('livewire:load', renderTurnstile);
    </script>
</x-filament-panels::page.simple>
