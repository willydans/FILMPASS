{{-- resources/views/layouts/header.blade.php --}}
<header class="bg-white shadow-md p-4 flex justify-between items-center">
    {{-- Logo / Judul --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            <a href="#">
                <span class="text-blue-600">FilmPass</span> Admin
            </a>
        </h1>
    </div>

    {{-- Ikon dan Avatar --}}
    <div class="flex items-center space-x-4">
        {{-- Ikon Lonceng --}}
        <button class="text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341A6.002 6.002 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </button>

        {{-- Avatar Admin dengan dropdown --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-600 text-white font-semibold">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </span>
                <span class="text-gray-700 font-medium">
                    {{ Auth::user()->name ?? 'Admin' }}
                </span>
            </button>

            {{-- Dropdown --}}
            <div x-show="open" @click.away="open = false"
                 x-transition
                 class="absolute right-0 mt-2 w-40 bg-white border rounded-md shadow-lg py-1 z-20">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

{{-- Alpine.js untuk toggle dropdown --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
