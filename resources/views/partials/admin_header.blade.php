<header class="bg-white p-4 border-b border-gray-200 flex justify-between items-center fixed top-0 w-full lg:w-[calc(100%-256px)] z-20 shadow-sm">
     <button id="menu-toggle" class="text-gray-500 hover:text-gray-700 lg:hidden p-1 mr-2">
        <i data-lucide="menu" class="w-6 h-6"></i>
    </button>
    <div class="flex-grow"></div> <div class="flex items-center space-x-4">
        <button class="text-gray-500 hover:text-gray-700">
            <i data-lucide="bell" class="w-5 h-5"></i>
        </button>
        
        <div x-data="{ open: false }" class="relative">
            
            <div @click="open = !open" class="flex items-center space-x-2 text-sm font-medium text-gray-700 cursor-pointer p-2 rounded-full hover:bg-gray-100 transition">
                <div class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">
                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                </div>
                <span>{{ Auth::user()->name ?? 'Admin' }}</span>
                <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-180': open }"></i>
            </div>

            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-100"
                style="display: none;">
                
                {{-- Form Logout (Wajib pakai Form untuk rute POST) --}}
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
        </div>
</header>