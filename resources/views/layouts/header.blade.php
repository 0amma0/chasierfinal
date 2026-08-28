@php
    $user = auth()->user();
    $isAdmin = $user && strtolower((string) $user->role) === 'admin';
    $isKasir = $user && strtolower((string) $user->role) === 'kasir';
    $myActiveSession = $isKasir
        ? \App\Models\CashSession::where('user_id', $user->id)->where('status', 'open')->first()
        : null;
@endphp

<nav class="bg-gray-800" x-data="{ openMobile: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            <div class="flex items-center">
                <div class="shrink-0 flex items-center gap-2">
                    <span class="font-bold text-white text-lg">Sinar Nusantara</span>
                </div>

                <div class="hidden md:block">
                    <div class="ml-8 flex items-center space-x-2">

                        @if($isAdmin)
                            <a href="{{ route('admin.home') }}"
                               class="rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin') || request()->routeIs('admin.cash-sessions.index') ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.products.index') }}"
                               class="rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.products.*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                Produk
                            </a>
                            <a href="{{ route('admin.suppliers.index') }}"
                               class="rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.suppliers.*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                Gudang
                            </a>
                        @endif

                        @if($isKasir)
                            @if($myActiveSession)
                                <a href="{{ route('admin.pos.index') }}"
                                   class="rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.pos.*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    Kasir (POS)
                                </a>
                                <a href="{{ route('admin.cash-sessions.show', $myActiveSession) }}"
                                   class="rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.cash-sessions.*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    Sesi Aktif
                                </a>
                            @else
                                <a href="{{ route('admin.cash-sessions.open-form') }}"
                                   class="rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs('admin.cash-sessions.open-form') ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    Buka Sesi Kasir
                                </a>
                            @endif
                        @endif

                    </div>
                </div>
            </div>

            @if($user)
                <div class="relative ml-3" x-data="{ open: false }">
                    <button @click="open = !open" type="button"
                        class="flex items-center gap-x-3 rounded-full bg-gray-700/60 p-1.5 text-sm text-white focus:outline-none hover:bg-gray-700 transition border border-gray-600/50 cursor-pointer">
                        <div class="h-8 w-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div class="hidden md:flex md:flex-col text-left pr-1">
                            <span class="text-xs font-bold text-white leading-none">{{ $user->name }}</span>
                            <span class="text-[10px] font-semibold text-indigo-300 capitalize mt-0.5">{{ $user->role }}</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                         style="display: none;"
                         class="absolute right-0 z-50 mt-2 w-52 origin-top-right rounded-2xl bg-white py-2 shadow-xl border border-gray-100">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs font-bold text-gray-900 truncate">{{ $user->name }}</p>
                            <p class="text-[11px] text-gray-500 truncate">{{ $user->email }}</p>
                            <p class="text-[10px] font-semibold text-indigo-600 capitalize mt-1">Role: {{ $user->role }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="pt-1">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-red-600 hover:bg-red-50 text-left transition cursor-pointer">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>

                <div class="-mr-2 flex md:hidden">
                    <button @click="openMobile = !openMobile" type="button"
                        class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-700 hover:text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>

    @if($user)
        <div x-show="openMobile" style="display: none;" class="md:hidden bg-gray-800 border-t border-gray-700">
            <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
@if($isAdmin)
                    <a href="{{ route('admin.home') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Dashboard</a>
                    <a href="{{ route('admin.products.index') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Produk</a>
                    <a href="{{ route('admin.suppliers.index') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Supplier</a>
                @endif

                @if($isKasir)
                    @if($myActiveSession)
                        <a href="{{ route('admin.pos.index') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Kasir (POS)</a>
                        <a href="{{ route('admin.cash-sessions.show', $myActiveSession) }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Sesi Aktif</a>
                    @else
                        <a href="{{ route('admin.cash-sessions.open-form') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Buka Sesi Kasir</a>
                    @endif
                @endif
            </div>

            <div class="border-t border-gray-700 pt-4 pb-3">
                <div class="flex items-center px-5">
                    <div class="h-9 w-9 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-white leading-none">{{ $user->name }}</div>
                        <div class="text-xs font-medium text-gray-400 mt-1 capitalize">{{ $user->role }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1 px-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left rounded-md px-3 py-2 text-base font-medium text-red-400 hover:bg-gray-700">Log Out</button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</nav>

<header class="bg-white shadow-sm border-b border-gray-200">
    <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">
            @yield('header_title', 'Dashboard')
        </h1>
    </div>
</header>