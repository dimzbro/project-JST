<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Job Sharing Task') }} - Dashboard</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white text-gray-900 flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-[#f4f8fe] flex-shrink-0 h-full border-r border-gray-100 flex flex-col">
            <div class="p-6 flex items-center">
                <div class="w-10 h-10 bg-blue-200 text-blue-600 font-bold text-xl flex items-center justify-center italic mr-3" style="font-family: serif;">JST</div>
                <h1 class="text-xl font-bold text-blue-500">Job Sharing Task</h1>
            </div>

            <div class="px-6 pb-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                MENU UTAMA
            </div>

            <nav class="flex-1 px-4 space-y-1 overflow-y-auto">
                @if(session('active_role') === 'admin')
                    <a href="{{ route('dashboard') }}" class="flex items-center px-2 py-3 text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-gray-800 bg-white shadow-sm font-semibold' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }} rounded-md">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-gray-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Admin Panel
                    </a>
                    
                    <a href="#" class="flex items-center px-2 py-3 text-sm font-medium text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-md">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Verifikasi
                    </a>

                    <a href="#" class="flex items-center px-2 py-3 text-sm font-medium text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-md">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Pengguna
                    </a>

                    <a href="#" class="flex items-center px-2 py-3 text-sm font-medium text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-md">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        Monitoring
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="flex items-center px-2 py-3 text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-gray-800 bg-white shadow-sm font-semibold' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }} rounded-md">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-gray-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        Dashboard
                    </a>
                    
                    <a href="#" class="flex items-center px-2 py-3 text-sm font-medium text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-md">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Kelola Pekerjaan
                    </a>

                    <a href="#" class="flex items-center px-2 py-3 text-sm font-medium text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-md">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Tugas Saya
                    </a>

                    <a href="#" class="flex items-center px-2 py-3 text-sm font-medium text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-md">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Riwayat
                    </a>

                    <a href="{{ route('profile.edit') }}" class="flex items-center px-2 py-3 text-sm font-medium {{ request()->routeIs('profile.edit') ? 'text-gray-800 bg-white shadow-sm font-semibold' : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50' }} rounded-md">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('profile.edit') ? 'text-gray-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profil
                    </a>
                @endif
            </nav>
            
            <div class="px-4 py-4 border-t border-gray-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-2 py-3 text-sm font-medium text-red-500 hover:text-red-700 hover:bg-red-50 rounded-md">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 h-full overflow-y-auto">
            <header class="h-16 flex items-center justify-end px-8 border-b border-gray-100">
                <div class="flex items-center">
                    <span class="mr-3 text-sm font-medium text-gray-700">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                    <div class="w-8 h-8 rounded-full bg-gray-300 overflow-hidden flex-shrink-0">
                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover">
                        @else
                            <svg class="w-full h-full text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        @endif
                    </div>
                </div>
            </header>
            
            <div class="p-8">
                {{ $slot }}
            </div>
        </main>
        
    </body>
</html>
