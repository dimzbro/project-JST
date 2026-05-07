<x-app-layout>
    @if($role === 'admin')
        
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-1">Admin Dashboard</h2>
            <p class="text-gray-500 text-sm">Selamat datang kembali, berikut adalah ringkasan performa platform hari ini.</p>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 bg-blue-100 text-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-gray-400 text-xs font-semibold mb-1 uppercase tracking-wider">Total Pengguna</h3>
                <span class="text-2xl font-bold text-gray-800">{{ number_format($totalUsers, 0, ',', '.') }}</span>
            </div>

            
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 bg-orange-100 text-orange-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" border="none" fill="currentColor" viewBox="0 0 24 24"><path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2zm-9 3a1 1 0 011 1v2a1 1 0 11-2 0v-2a1 1 0 011-1zM8 7h8V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2z" /></svg>
                    </div>
                </div>
                <h3 class="text-gray-400 text-xs font-semibold mb-1 uppercase tracking-wider">Lowongan Aktif</h3>
                <span class="text-2xl font-bold text-gray-800">{{ number_format($adminActiveJobs, 0, ',', '.') }}</span>
            </div>

            
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 bg-green-100 text-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <h3 class="text-gray-400 text-xs font-semibold mb-1 uppercase tracking-wider">Tugas Selesai</h3>
                <span class="text-2xl font-bold text-gray-800">{{ number_format($adminCompletedTasks, 0, ',', '.') }}</span>
            </div>

            
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 bg-purple-100 text-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                </div>
                <h3 class="text-gray-400 text-xs font-semibold mb-1 uppercase tracking-wider">Total Transaksi</h3>
                <span class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalTransactions, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Tren Pertumbuhan Platform</h3>
                    <p class="text-xs text-gray-500">Statistik pendaftaran pengguna dan postingan baru</p>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-80 relative overflow-hidden flex items-end justify-center">
                    
                    <svg class="w-full h-48 absolute bottom-6 left-0 right-0" preserveAspectRatio="none" viewBox="0 0 100 100">
                        <path d="M0,80 Q10,70 20,80 T40,60 T60,70 T80,50 T100,20 L100,100 L0,100 Z" fill="rgba(59, 130, 246, 0.1)" />
                        <path d="M0,80 Q10,70 20,80 T40,60 T60,70 T80,50 T100,20" fill="none" stroke="#3b82f6" stroke-width="2" />
                        
                        <path d="M0,90 Q15,85 30,90 T50,80 T70,85 T90,75 L100,70 L100,100 L0,100 Z" fill="rgba(14, 165, 233, 0.1)" />
                        <path d="M0,90 Q15,85 30,90 T50,80 T70,85 T90,75 L100,70" fill="none" stroke="#0ea5e9" stroke-width="2" stroke-dasharray="2,2" />
                    </svg>
                    <div class="absolute bottom-2 left-0 w-full flex justify-center space-x-6 text-xs text-gray-400">
                        <span class="flex items-center"><span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>Pengguna Baru</span>
                        <span class="flex items-center"><span class="w-2 h-2 bg-sky-500 rounded-full mr-2"></span>Lowongan Baru</span>
                    </div>
                </div>
            </div>
            
            <div>
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Log Aktivitas Sistem</h3>
                    <p class="text-xs text-gray-500">Menampilkan semua kejadian teknis terbaru di platform.</p>
                </div>
                <div class="space-y-4">
                    @forelse($activityLogs as $log)
                        <div class="flex items-start">
                            @php
                                $colorClass = 'bg-blue-500';
                                if($log->type == 'project') $colorClass = 'bg-orange-500';
                                if($log->type == 'task') $colorClass = 'bg-green-500';
                                if($log->type == 'transaction') $colorClass = 'bg-purple-500';
                            @endphp
                            <div class="w-2 h-2 mt-1.5 {{ $colorClass }} rounded-full mr-3 flex-shrink-0"></div>
                            <div>
                                <p class="text-sm text-gray-800">{!! $log->description !!}</p>
                                <span class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">Belum ada aktivitas sistem yang terekam hari ini.</p>
                    @endforelse
                </div>
                <div class="mt-6 flex justify-between items-center text-xs text-gray-500">
                    <span>Auto-update diaktifkan (5s)</span>
                </div>
            </div>
        </div>
    @else
        
        <div class="mb-8 w-full bg-[#007bff] rounded-lg p-6 text-white shadow-sm">
            <h2 class="text-2xl font-bold mb-1">Selamat Datang {{ $user->first_name }} {{ $user->last_name }}!</h2>
            <p class="text-blue-100 text-sm">Berikut adalah ringkasan aktivitas Anda hari ini.</p>
        </div>

       
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            
            <div class="bg-white border text-center border-gray-200 rounded-lg p-6 shadow-sm flex flex-col justify-center items-start">
                <div class="flex items-center mb-2">
                    <div class="w-12 h-12 bg-blue-100 text-blue-500 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <span class="text-4xl font-bold">{{ $activeJobsCount }}</span>
                </div>
                <h3 class="text-gray-800 font-bold mb-1">Pekerjaan Aktif</h3>
                <p class="text-xs text-gray-500">Tugas yang sedang Anda kerjakan</p>
            </div>

            
            <div class="bg-white border text-center border-gray-200 rounded-lg p-6 shadow-sm flex flex-col justify-center items-start">
                <div class="flex items-center mb-2">
                    <div class="w-12 h-12 bg-yellow-100 text-yellow-500 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="text-4xl font-bold">{{ $postedJobsCount }}</span>
                </div>
                <h3 class="text-gray-800 font-bold mb-1">Pekerjaan Terpasang</h3>
                <p class="text-xs text-gray-500">Pekerjaan yang anda posting</p>
            </div>

            
            <div class="bg-white border text-center border-gray-200 rounded-lg p-6 shadow-sm flex flex-col justify-center items-start">
                <div class="flex items-center mb-2">
                    <div class="w-12 h-12 bg-green-100 text-green-500 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-4xl font-bold">{{ $completedJobsCount }}</span>
                </div>
                <h3 class="text-gray-800 font-bold mb-1">Pekerjaan Selesai</h3>
                <p class="text-xs text-gray-500">Total pekerjaan yang berhasil</p>
            </div>
        </div>

       
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
           
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm flex flex-col {{ $role === 'worker' ? 'opacity-50 pointer-events-none' : '' }}">
                <div class="flex items-center mb-3 text-gray-800">
                    <h3 class="text-lg font-bold mr-2">Pasang Lowongan Baru</h3>
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-sm text-gray-500 mb-6 flex-1">Butuh bantuan untuk menyelesaikan proyek? Posting lowongan Anda sekarang dan temukan tenaga ahli terbaik.</p>
                @if($role === 'client')
                    <a href="{{ route('projects.create') }}" class="w-full text-center py-2.5 px-4 rounded-md text-sm font-medium transition-colors bg-[#5bc0de] text-white hover:bg-blue-400 block">
                        Pasang Sekarang
                    </a>
                @else
                    <button class="w-full py-2.5 px-4 rounded-md text-sm font-medium transition-colors bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed">
                        Pasang Sekarang
                    </button>
                @endif
            </div>

           
            <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm flex flex-col {{ $role === 'client' ? 'opacity-50 pointer-events-none' : '' }}">
                <div class="flex items-center mb-3 text-gray-800">
                    <h3 class="text-lg font-bold mr-2">Cari Lowongan Kerja</h3>
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <p class="text-sm text-gray-500 mb-6 flex-1">Butuh bantuan untuk menyelesaikan proyek? Posting lowongan Anda sekarang dan temukan tenaga ahli terbaik.</p>
                @if($role === 'worker')
                    <a href="{{ route('jobs.index') }}" class="w-full text-center py-2.5 px-4 rounded-md text-sm font-medium transition-colors border-2 bg-white border-blue-400 text-blue-500 hover:bg-blue-50 block">
                        Cari Lowongan
                    </a>
                @else
                    <button class="w-full py-2.5 px-4 rounded-md text-sm font-medium transition-colors border-2 bg-gray-100 border-gray-200 text-gray-400 cursor-not-allowed">
                        Cari Lowongan
                    </button>
                @endif
            </div>

        </div>
    @endif
</x-app-layout>
