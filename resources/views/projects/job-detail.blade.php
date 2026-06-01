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

               
                @if(isset($task) && $task)
                    <a href="{{ route('worker.chat.show', $task->id) }}" class="w-full flex items-center justify-center bg-[#f8f9fa] hover:bg-[#e9ecef] text-[#333] border border-[#ced4da] font-medium py-3 px-4 rounded-lg shadow-sm mb-3 transition-colors text-sm">
                        Hubungi Client
                    </a>
                @else
                    <button type="button" onclick="document.getElementById('confirmModal').style.display='flex'" class="bg-[#5bc0de] hover:bg-[#4eb0ce] text-white font-bold shadow transition-colors" style="width: 100%; padding: 12px 16px; border-radius: 12px; font-size: 14px; border: none; cursor: pointer; margin-bottom: 8px;">
                        Ambil Pekerjaan
                    </button>
                    
                    <p class="text-gray-400" style="text-align: center; font-size: 10px; line-height: 1.3; padding: 0 8px;">
                        Dengan menekan tombol di atas, Anda menyetujui syarat & ketentuan layanan
                    </p>
                @endif

            </div>

        </div>
    </div>

   
    <div id="confirmModal" style="display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center; background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);">
        <div id="confirmModalContent" style="background: white; border-radius: 20px; padding: 32px; max-width: 400px; width: 90%; text-align: center; box-shadow: 0 25px 60px rgba(0,0,0,0.15); animation: modalIn 0.3s ease;">
            
            
            <div style="width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, #e0f4fb, #b8e8f7); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <svg style="width: 32px; height: 32px; color: #3ba8c9;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            
            <h3 style="font-size: 20px; font-weight: 700; color: #1a1a1a; margin-bottom: 8px;">Konfirmasi</h3>
            
           
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 28px; line-height: 1.5;">
                Apakah Anda yakin ingin mengambil pekerjaan ini?
            </p>

           
            <div style="display: flex; gap: 12px; justify-content: center;">
                <button type="button" onclick="document.getElementById('confirmModal').style.display='none'" style="flex: 1; padding: 12px 20px; border-radius: 12px; border: 1px solid #e5e7eb; background: white; color: #374151; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    Batal
                </button>
                <form method="POST" action="{{ route('jobs.take', $project->id) }}" style="flex: 1; margin: 0;">
                    @csrf
                    <button type="submit" style="width: 100%; padding: 12px 20px; border-radius: 12px; border: none; background: linear-gradient(135deg, #5bc0de, #45a5c4); color: white; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(91,192,222,0.3);" onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 16px rgba(91,192,222,0.4)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 12px rgba(91,192,222,0.3)'">
                        Ya, Ambil
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.9) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>

</x-app-layout>
