<body class="fi-body min-h-full bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-white">
    <div
        x-data="{ sidebarOpen: true }"
        class="flex min-h-screen"
    >
        <!-- ⏬ TOGGLE BUTTON -->
        <button
            @click="sidebarOpen = !sidebarOpen"
            class="z-50 fixed top-4 left-4 bg-red-500 text-white border p-2 shadow rounded block"
        >
            ☰
        </button>

        <!-- SIDEBAR -->
        <aside
            x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fi-sidebar hidden lg:flex w-64 shrink-0 flex-col bg-white dark:bg-gray-900 border-r dark:border-gray-800"
        >
            {{ $this->renderSidebar() }}
        </aside>

        <!-- MAIN -->
        <div class="flex flex-1 flex-col">
            {{ $this->renderHeader() }}

            <main class="fi-main flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
