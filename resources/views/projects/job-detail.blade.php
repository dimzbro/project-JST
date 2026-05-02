<x-app-layout :hideSidebar="true">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('jobs.index') }}" class="text-[13px] text-gray-600 hover:text-[#5bc0de] mb-4 inline-block font-medium">
                Kembali
            </a>
            
            <h1 class="text-3xl font-bold text-gray-800 mb-6 tracking-tight">Detail Pekerjaan</h1>

            <!-- Client Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div class="flex items-center mb-3 sm:mb-0">
                    <div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden mr-4 flex-shrink-0">
                        @if($project->client && $project->client->profile_photo_path)
                            <img src="{{ Storage::url($project->client->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover">
                        @else
                            <svg class="w-full h-full text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-[15px] leading-tight">
                            {{ $project->client ? $project->client->first_name . ' ' . $project->client->last_name : 'Klien Tidak Diketahui' }}
                        </h3>
                        <div class="flex items-center text-sm text-gray-500 mt-0.5">
                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            @if($project->client && $project->client->rating)
                                <span class="font-medium text-gray-700">{{ number_format($project->client->rating, 1) }}</span><span class="text-gray-400">/10.0</span>
                            @else
                                <span class="font-medium text-gray-500 italic">Belum ada rating</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="text-right w-full sm:w-auto">
                    <p class="text-[13px] text-gray-500">Di posting</p>
                    <p class="text-[13px] font-medium text-gray-700">{{ $project->created_at ? $project->created_at->diffForHumans() : '-' }}</p>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 text-red-700 bg-red-100 rounded-lg border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Left Content: Job Details -->
            <div class="lg:w-2/3 flex-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 h-full">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $project->title }}</h2>
                    
                    <div class="border border-gray-200 rounded-xl p-6">
                        <div class="mb-6">
                            <h4 class="text-[15px] font-bold text-gray-900 mb-1">Kategori</h4>
                            <p class="text-[14px] text-gray-500">{{ $project->category ?? 'Tidak ada kategori' }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-[15px] font-bold text-gray-900 mb-3">Deskripsi Pekerjaan</h4>
                            <div class="text-[14px] text-gray-500 leading-relaxed whitespace-pre-line">
                                {{ $project->description }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content: Action & Budget -->
            <div class="lg:w-1/3 w-full shrink-0">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                    <h4 class="text-[13px] text-gray-500 mb-1">Anggaran Proyek</h4>
                    <p class="text-2xl font-bold text-gray-900 mb-6">
                        Rp. {{ number_format($project->budget ?? 0, 0, '.', '.') }}
                    </p>
                    
                    @if($project->deadline)
                        <div class="flex justify-between items-center py-2 border-t border-gray-100">
                            <span class="text-[13px] text-gray-500">Deadline</span>
                            <span class="text-[13px] font-medium text-gray-700">{{ \Carbon\Carbon::parse($project->deadline)->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-t border-gray-100">
                            <span class="text-[13px] text-gray-500">Tenggat</span>
                            <span class="text-[13px] font-medium text-gray-700">
                                @php
                                    $deadlineDate = \Carbon\Carbon::parse($project->deadline)->endOfDay();
                                    $days = (int) now()->diffInDays($deadlineDate, false);
                                @endphp
                                @if($days > 0)
                                    {{ $days }} hari lagi
                                @elseif($days === 0)
                                    Hari ini
                                @else
                                    Terlewat
                                @endif
                            </span>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('jobs.take', $project->id) }}" class="mb-4">
                    @csrf
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengambil pekerjaan ini?')" class="w-full bg-[#5bc0de] hover:bg-[#4eb0ce] text-white font-bold py-3.5 px-4 rounded-lg shadow transition-colors text-[16px]">
                        Ambil Pekerjaan
                    </button>
                </form>
                
                <p class="text-center text-[12px] text-gray-400 leading-tight px-4">
                    Dengan menekan tombol di atas, Anda menyetujui syarat & ketentuan layanan
                </p>

            </div>

        </div>
    </div>
</x-app-layout>
