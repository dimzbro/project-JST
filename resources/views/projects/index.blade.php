<x-app-layout :hideSidebar="true">
    <div class="w-full max-w-full">

        <div class="mb-8">
            <a href="{{ route('dashboard') }}" class="text-[13px] text-gray-700 hover:text-[#5bc0de] mb-4 inline-block font-medium">
                Kembali
            </a>
            
            <h1 class="text-[28px] md:text-[32px] font-bold text-gray-900 mb-1 leading-tight tracking-tight">Eksplorasi Pekerjaan</h1>
            <p class="text-[14px] md:text-[15px] text-gray-500">Temukan pekerjaan yang sesuai dengan Anda. Ada lebih dari 5 Ribu pekerjaan aktif hari ini yang siap Anda ambil.</p>
        </div>

        @if(session('error'))
            <div class="mb-6 w-full p-4 text-red-700 bg-red-100 rounded-lg border border-red-200">
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="mb-6 w-full p-4 text-green-700 bg-green-100 rounded-lg border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('jobs.index') }}" method="GET" class="mb-8 w-full">
            <!-- Search Bar -->
            <div class="flex flex-col md:flex-row gap-3 mb-4 w-full">
                <div class="relative flex-1 w-full">
                    <div id="search-icon-wrapper" class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-opacity duration-200">
                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="search-input" name="search" value="{{ request('search') }}" class="block w-full pl-11 pr-3 py-3 border border-gray-200 rounded-lg leading-5 bg-white focus:outline-none focus:border-[#5bc0de] focus:ring-1 focus:ring-[#5bc0de] text-[15px] shadow-sm transition-all duration-200" placeholder="">
                </div>
                <button type="submit" class="w-full md:w-auto bg-[#5bc0de] hover:bg-[#4eb0ce] text-white font-medium py-3 px-8 rounded-lg transition-colors text-[14px] shadow-sm flex-shrink-0">
                    Cari Sekarang
                </button>
            </div>

            <!-- Categories -->
            <div class="flex flex-wrap gap-2.5 items-center w-full">
                <a href="{{ route('jobs.index', ['search' => request('search')]) }}" 
                   class="px-4 py-2 rounded-full text-[13px] font-semibold transition-colors border {{ !request('category') ? 'bg-white border-gray-300 text-gray-900 shadow-sm' : 'bg-transparent border-transparent text-gray-600 hover:bg-gray-100' }}">
                    Semua
                </a>
                
                @foreach($categories as $cat)
                    <a href="{{ route('jobs.index', ['category' => $cat, 'search' => request('search')]) }}" 
                       class="px-4 py-2 rounded-full text-[13px] font-semibold transition-colors border {{ request('category') == $cat ? 'bg-white border-gray-300 text-gray-900 shadow-sm' : 'bg-transparent border-transparent text-gray-600 hover:bg-gray-100' }}">
                        {{ $cat }}
                    </a>
                @endforeach
                
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
            </div>
        </form>

        <!-- Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 w-full gap-5">
            @forelse($projects as $project)
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-200 hover:shadow-md transition flex flex-col h-full w-full">
                    <div class="flex-1">
                        <h3 class="text-[16px] font-bold text-gray-900 leading-snug mb-1">{{ $project->title }}</h3>
                        <p class="text-[13px] text-gray-700 mb-4">{{ $project->category ?? 'Tidak ada kategori' }}</p>
                        
                        <p class="text-[13px] text-gray-500 mb-6 line-clamp-3 leading-relaxed">
                            {{ $project->description }}
                        </p>
                        
                        <div class="space-y-0.5 mb-6">
                            <p class="text-[14px] font-medium text-gray-900">
                                Rp. {{ number_format($project->budget ?? 0, 0, '.', '.') }}
                            </p>
                            @if($project->deadline)
                                <p class="text-[13px] text-gray-500">
                                    Deadline: {{ \Carbon\Carbon::parse($project->deadline)->translatedFormat('d M Y') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-end mt-auto">
                        <span class="text-[12px] italic text-gray-400">Klik untuk detail</span>
                        <a href="{{ route('worker.jobs.show', $project->id) }}" class="text-[13px] font-medium text-[#5bc0de] hover:text-[#4eb0ce] transition-colors flex items-center bg-transparent border-none cursor-pointer">
                            Lihat <svg class="w-3.5 h-3.5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 xl:col-span-4 2xl:col-span-5 bg-white border border-gray-200 rounded-xl p-12 shadow-sm text-center w-full">
                    <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Pekerjaan tidak ditemukan</h3>
                    <p class="text-gray-500 text-[14px] max-w-md mx-auto">Kami tidak menemukan pekerjaan yang sesuai dengan pencarian Anda. Silakan coba kata kunci atau kategori lain.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Script for Search Icon Behavior -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const searchIconWrapper = document.getElementById('search-icon-wrapper');
            let timeoutId;

            function updateIconState() {
                const hasFocus = document.activeElement === searchInput;
                const hasText = searchInput.value.trim() !== '';

                clearTimeout(timeoutId);

                if (hasFocus || hasText) {
                    searchIconWrapper.style.opacity = '0';
                    timeoutId = setTimeout(() => { 
                        if (document.activeElement === searchInput || searchInput.value.trim() !== '') {
                            searchIconWrapper.style.display = 'none'; 
                        }
                    }, 200);
                } else {
                    searchIconWrapper.style.display = 'flex';
                    setTimeout(() => { 
                        searchIconWrapper.style.opacity = '1'; 
                    }, 10);
                }
            }

            if (searchInput) {
                // Initial state check
                updateIconState();
                
                // Event listeners for input changes, focus, and blur
                searchInput.addEventListener('input', updateIconState);
                searchInput.addEventListener('focus', updateIconState);
                searchInput.addEventListener('blur', updateIconState);
            }
        });
    </script>
</x-app-layout>
