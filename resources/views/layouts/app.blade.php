<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Flash messages untuk SweetAlert2 --}}
    @if(session('success'))
        <meta name="flash-success" content="{{ session('success') }}">
    @endif
    @if(session('error'))
        <meta name="flash-error" content="{{ session('error') }}">
    @endif
    @if(session('info'))
        <meta name="flash-info" content="{{ session('info') }}">
    @endif

    <title>@yield('title', 'Salon Kecantikan') — Nude Beauty</title>
    <meta name="description" content="@yield('meta_description', 'Salon kecantikan premium dengan layanan terbaik untuk merawat kecantikan Anda.')">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>
<body>

@if(auth()->check() && auth()->user()->isAdmin())
    {{-- ═══ ADMIN LAYOUT ═══ --}}
    <div class="flex">
        {{-- Sidebar --}}
        <aside id="admin-sidebar" class="admin-sidebar">
            {{-- Logo --}}
            <div class="px-6 py-8 border-b border-white/10">
                <a href="/admin/dashboard" class="block">
                    <span class="font-serif text-cream text-xl italic">Nude</span>
                    <span class="block text-xs tracking-widest uppercase text-cream/50 mt-0.5">Admin Panel</span>
                </a>
            </div>

            {{-- Nav --}}
            <nav class="py-4">
                <div class="px-4 py-2 mb-1">
                    <span class="text-cream/30 text-xs tracking-widest uppercase font-semibold">Utama</span>
                </div>
                <a href="/admin/dashboard" class="sidebar-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <div class="px-4 py-2 mt-4 mb-1">
                    <span class="text-cream/30 text-xs tracking-widest uppercase font-semibold">Verifikasi</span>
                </div>
                <a href="/admin/users" class="sidebar-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Akun Pelanggan
                    @php $pendingUsers = \App\Models\User::where('role','pelanggan')->where('account_status','pending')->count(); @endphp
                    @if($pendingUsers > 0)
                        <span class="ml-auto bg-tan text-white text-xs rounded-full px-2 py-0.5">{{ $pendingUsers }}</span>
                    @endif
                </a>
                <a href="/admin/reservations" class="sidebar-link {{ request()->is('admin/reservations*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Reservasi
                    @php $pendingRes = \App\Models\Reservation::where('status','pending')->count(); @endphp
                    @if($pendingRes > 0)
                        <span class="ml-auto bg-tan text-white text-xs rounded-full px-2 py-0.5">{{ $pendingRes }}</span>
                    @endif
                </a>
                <a href="/admin/payments" class="sidebar-link {{ request()->is('admin/payments*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Pembayaran
                    @php $pendingPay = \App\Models\Payment::where('status','pending')->count(); @endphp
                    @if($pendingPay > 0)
                        <span class="ml-auto bg-tan text-white text-xs rounded-full px-2 py-0.5">{{ $pendingPay }}</span>
                    @endif
                </a>

                <div class="px-4 py-2 mt-4 mb-1">
                    <span class="text-cream/30 text-xs tracking-widest uppercase font-semibold">Kelola</span>
                </div>
                <a href="/admin/announcements" class="sidebar-link {{ request()->is('admin/announcements*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    Pengumuman
                </a>
                <a href="/admin/treatments" class="sidebar-link {{ request()->is('admin/treatments*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Treatment
                </a>
            </nav>

            {{-- Logout --}}
            <div class="absolute bottom-0 left-0 right-0 px-4 py-4 border-t border-white/10">
                <div class="text-cream/50 text-xs mb-3 px-2">{{ auth()->user()->name }}</div>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="sidebar-link w-full text-left hover:text-red-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile overlay --}}
        <div id="sidebar-overlay" class="hidden fixed inset-0 bg-black/40 z-30 md:hidden"></div>

        {{-- Main Content --}}
        <main class="admin-content flex-1">
            {{-- Top bar --}}
            <header class="bg-white border-b border-graymedium px-6 py-4 flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <button id="sidebar-toggle" class="md:hidden text-charcoal-light hover:text-charcoal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h1 class="font-serif text-xl text-charcoal">@yield('page-title', 'Dashboard')</h1>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-charcoal-light tracking-wide">{{ auth()->user()->name }}</span>
                    <div class="w-8 h-8 rounded-full bg-blush flex items-center justify-center">
                        <span class="text-charcoal text-xs font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                </div>
            </header>

            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>

@else
    {{-- ═══ PELANGGAN LAYOUT ═══ --}}
    <nav class="navbar">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            {{-- Logo --}}
            <a href="/" class="flex flex-col leading-none">
                <span class="font-serif text-2xl italic text-charcoal tracking-tight">Nude</span>
                <span class="text-xs tracking-widest uppercase text-tan-dark" style="font-size:0.55rem; letter-spacing:0.18em;">Beauty Salon</span>
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-8">
                @auth
                    <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
                    <a href="/treatments" class="nav-link {{ request()->is('treatments') ? 'active' : '' }}">Layanan</a>
                    <a href="/reservations" class="nav-link {{ request()->is('reservations*') ? 'active' : '' }}">Reservasi</a>
                    <a href="/announcements" class="nav-link {{ request()->is('announcements') ? 'active' : '' }}">Pengumuman</a>
                @else
                    <a href="/#layanan" class="nav-link">Layanan</a>
                    <a href="/#tentang" class="nav-link">Tentang</a>
                @endauth
            </div>

            {{-- Right: Status + Auth --}}
            <div class="flex items-center gap-4">
                @auth
                    {{-- Status badge --}}
                    @if(auth()->user()->account_status === 'pending')
                        <a href="/status-akun" class="badge-pending text-xs">Menunggu Verifikasi</a>
                    @elseif(auth()->user()->account_status === 'rejected')
                        <a href="/status-akun" class="badge-rejected text-xs">Akun Ditolak</a>
                    @endif

                    {{-- User dropdown --}}
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-xs tracking-widest uppercase text-charcoal-light hover:text-charcoal transition-colors">
                            <div class="w-7 h-7 rounded-full bg-blush flex items-center justify-center">
                                <span class="text-charcoal text-xs font-semibold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <span class="hidden sm:block">{{ auth()->user()->name }}</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="absolute right-0 top-full mt-2 w-44 bg-white border border-graymedium shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="/status-akun" class="block px-4 py-2.5 text-xs tracking-wide uppercase hover:bg-graylight text-charcoal-light hover:text-charcoal transition-colors">Status Akun</a>
                            <div class="border-t border-graymedium"></div>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-xs tracking-wide uppercase hover:bg-graylight text-charcoal-light hover:text-charcoal transition-colors">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="/login" class="nav-link">Masuk</a>
                    <a href="/register" class="btn-nude">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-charcoal text-cream/70 mt-24">
        <div class="max-w-6xl mx-auto px-6 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                {{-- Brand --}}
                <div class="md:col-span-2">
                    <span class="font-serif text-cream text-2xl italic">Nude</span>
                    <span class="block text-xs tracking-widest uppercase text-cream/40 mt-0.5 mb-4">Beauty Salon</span>
                    <p class="text-sm leading-relaxed text-cream/60 max-w-xs">
                        Salon kecantikan premium yang menghadirkan perawatan terbaik untuk merawat dan memperindah kecantikan alami Anda.
                    </p>
                    <div class="mt-6 flex gap-3">
                        <a href="#" class="w-8 h-8 border border-cream/20 flex items-center justify-center hover:border-tan hover:text-tan transition-colors">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.6a10 10 0 01-2.9.8 5 5 0 002.2-2.8c-1 .6-2.1 1-3.2 1.2a5 5 0 00-8.5 4.6A14.1 14.1 0 011.6 3.2a5 5 0 001.5 6.7 5 5 0 01-2.2-.6v.1a5 5 0 004 4.9 5 5 0 01-2.2.1 5 5 0 004.7 3.5A10 10 0 010 19.5a14.1 14.1 0 007.6 2.2c9.1 0 14-7.5 14-14v-.6c1-.7 1.8-1.6 2.4-2.5z"/></svg>
                        </a>
                        <a href="#" class="w-8 h-8 border border-cream/20 flex items-center justify-center hover:border-tan hover:text-tan transition-colors">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.2c3.2 0 3.6 0 4.9.1 3.3.1 4.8 1.7 4.9 4.9.1 1.3.1 1.6.1 4.8 0 3.2 0 3.6-.1 4.8-.1 3.2-1.7 4.8-4.9 4.9-1.3.1-1.6.1-4.9.1-3.2 0-3.6 0-4.8-.1-3.3-.1-4.8-1.7-4.9-4.9C2.2 15.6 2.2 15.3 2.2 12c0-3.2 0-3.6.1-4.8C2.4 3.9 4 2.3 7.2 2.3 8.4 2.2 8.8 2.2 12 2.2zM12 0C8.7 0 8.3 0 7.1.1 2.7.3.3 2.7.1 7.1 0 8.3 0 8.7 0 12c0 3.3 0 3.7.1 4.9.2 4.4 2.6 6.8 7 7C8.3 24 8.7 24 12 24c3.3 0 3.7 0 4.9-.1 4.4-.2 6.8-2.6 7-7 .1-1.2.1-1.6.1-4.9 0-3.3 0-3.7-.1-4.9C23.7 2.7 21.3.3 16.9.1 15.7 0 15.3 0 12 0zm0 5.8a6.2 6.2 0 100 12.4A6.2 6.2 0 0012 5.8zM12 16a4 4 0 110-8 4 4 0 010 8zm6.4-11.8a1.4 1.4 0 100 2.8 1.4 1.4 0 000-2.8z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Links --}}
                <div>
                    <h4 class="text-cream text-xs tracking-widest uppercase font-semibold mb-4">Layanan</h4>
                    <ul class="space-y-2.5">
                        <li><a href="/treatments" class="text-xs hover:text-tan transition-colors">Facial Treatment</a></li>
                        <li><a href="/treatments" class="text-xs hover:text-tan transition-colors">Body Massage</a></li>
                        <li><a href="/treatments" class="text-xs hover:text-tan transition-colors">Hair Treatment</a></li>
                        <li><a href="/treatments" class="text-xs hover:text-tan transition-colors">Manikur & Pedikur</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-cream text-xs tracking-widest uppercase font-semibold mb-4">Info</h4>
                    <ul class="space-y-2.5">
                        <li><a href="/register" class="text-xs hover:text-tan transition-colors">Daftar Akun</a></li>
                        <li><a href="/login" class="text-xs hover:text-tan transition-colors">Login</a></li>
                        <li><a href="/announcements" class="text-xs hover:text-tan transition-colors">Pengumuman</a></li>
                        <li class="text-xs text-cream/40">Jam: 09.00 – 20.00</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-cream/10 mt-12 pt-6 text-center">
                <p class="text-xs text-cream/30 tracking-wide">© {{ date('Y') }} Nude Beauty Salon. All rights reserved.</p>
            </div>
        </div>
    </footer>
@endif

</body>
</html>
