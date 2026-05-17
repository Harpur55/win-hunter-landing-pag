<x-filament-panels::page.simple>

    <div class="min-h-screen flex items-center justify-center px-4">

        <div class="w-full max-w-md bg-green-800/30">

            {{-- Card --}}
            <div class="bg-green-800/30 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl p-6 sm:p-8 transition-all">

                {{-- Logo + Title --}}
                <div class="text-center mb-6">
                    <img 
                        src="{{ asset('assets/images/download.jpg') }}" 
                        alt="Logo"
                        class="mx-auto h-16 w-16 rounded-full border-2 border-white/30 shadow-lg object-cover"
                    >

                    <h2 class="mt-5 text-2xl sm:text-3xl font-bold text-white">
                        Login Dashboard
                    </h2>

                    <p class="mt-2 text-sm text-gray-300">
                        Silakan login untuk melanjutkan
                    </p>
                </div>

                {{-- Form --}}
                <x-filament-panels::form 
                    wire:submit="authenticate" 
                    class="space-y-5"
                >
                    {{ $this->form }}

                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 text-gray-300">
                            <input type="checkbox" class="rounded border-gray-500 bg-gray-700">
                            Remember me
                        </label>

                        <a href="#" class="text-primary-400 hover:text-primary-300">
                            Lupa Password?
                        </a>
                    </div>

                    {{-- Button --}}
                    <button 
                        type="submit" 
                        class="w-full py-3 rounded-xl bg-blue-600 text-white font-semibold shadow-lg hover:scale-[1.02] hover:shadow-xl transition-all duration-300"
                    >
                        Login
                    </button>

                </x-filament-panels::form>

            </div>

            {{-- Footer --}}
            <p class="mt-6 text-center text-xs text-gray-500">
                © {{ date('Y') }} Win Hunter
            </p>

        </div>

    </div>

</x-filament-panels::page.simple>