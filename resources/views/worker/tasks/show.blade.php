<x-app-layout>
    <div class="mb-4">
        <a href="{{ route('worker.tasks.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors mb-2">
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-800 mb-3 tracking-tight">Detail Pekerjaan</h1>
    </div>
    
    @if(session('success'))
        <div class="mb-4 p-3 text-green-700 bg-green-100 rounded-lg border border-green-200 text-sm">
            {{ session('success') }}
        </div>
    @endif
    
    <div style="display: flex; gap: 24px; align-items: flex-start;">
        <!-- Left Column -->
        <div style="flex: 1; min-width: 0;">
            <!-- Client Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6" style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px;">
                <div style="display: flex; align-items: center;">
                    <div class="bg-gray-200 rounded-full overflow-hidden flex-shrink-0" style="width: 48px; height: 48px; margin-right: 16px;">
                        @if($task->project->client && $task->project->client->profile_photo_path)
                            <img src="{{ Storage::url($task->project->client->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover">
                        @else
                            <svg class="w-full h-full text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900" style="font-size: 16px; line-height: 1.2;">
                            {{ $task->project->client ? $task->project->client->first_name . ' ' . $task->project->client->last_name : 'Klien Tidak Diketahui' }}
                        </h3>
                        <div style="display: flex; align-items: center; margin-top: 4px; font-size: 14px;" class="text-gray-500">
                            <svg style="width: 16px; height: 16px; margin-right: 4px;" class="text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            @if($task->project->client && $task->project->client->rating)
                                <span class="font-medium text-gray-700">{{ number_format($task->project->client->rating, 1) }}</span><span class="text-gray-400 ml-1">/10.0</span>
                            @else
                                <span class="font-medium text-gray-500 italic">Belum ada rating</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div style="text-align: right;">
                    <p style="font-size: 13px;" class="text-gray-500">Di posting</p>
                    <p style="font-size: 13px;" class="font-medium text-gray-700">{{ $task->project->created_at ? $task->project->created_at->locale('id')->diffForHumans() : '-' }}</p>
                </div>
            </div>

            <!-- Job details card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100" style="padding: 24px;">
                <h2 class="font-bold text-gray-900" style="font-size: 20px; margin-bottom: 20px;">{{ $task->project->title }}</h2>
                
                <div class="border border-gray-200 rounded-lg" style="padding: 20px;">
                    <div style="margin-bottom: 16px;">
                        <h4 class="font-bold text-gray-900" style="font-size: 16px; margin-bottom: 4px;">Kategori</h4>
                        <p class="text-gray-500" style="font-size: 14px;">{{ $task->project->category ?? 'Tidak ada kategori' }}</p>
                    </div>
                    
                    <div>
                        <h4 class="font-bold text-gray-900" style="font-size: 16px; margin-bottom: 6px;">Deskripsi Pekerjaan</h4>
                        <div class="text-gray-500 whitespace-pre-line" style="font-size: 14px; line-height: 1.6;">{{ $task->project->description }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column -->
        <div style="width: 320px; flex-shrink: 0;">
            <!-- Budget Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200" style="padding: 20px; margin-bottom: 16px;">
                <h4 class="text-gray-500" style="font-size: 12px; margin-bottom: 4px;">Anggaran Proyek</h4>
                <p class="font-bold text-gray-900" style="font-size: 24px; margin-bottom: 16px;">
                    Rp. {{ number_format($task->project->budget ?? 0, 0, '.', '.') }}
                </p>
                
                @if($task->project->deadline)
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span class="text-gray-500" style="font-size: 12px;">Deadline</span>
                        <span class="font-medium text-gray-700" style="font-size: 12px;">{{ \Carbon\Carbon::parse($task->project->deadline)->translatedFormat('d M Y') }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="text-gray-500" style="font-size: 12px;">Tenggat</span>
                        <span class="font-medium text-gray-700" style="font-size: 12px;">
                            @php
                                $deadlineDate = \Carbon\Carbon::parse($task->project->deadline)->endOfDay();
                                $days = (int) now()->diffInDays($deadlineDate, false);
                            @endphp
                            @if($days > 0)
                                {{ $days }} Hari
                            @elseif($days === 0)
                                Hari ini
                            @else
                                Terlewat
                            @endif
                        </span>
                    </div>
                @endif
            </div>

            <!-- Buttons -->
            <button type="button" class="w-full bg-[#f8f9fa] hover:bg-[#e9ecef] text-[#333] border border-[#ced4da] font-medium py-3 px-4 rounded-lg shadow-sm mb-3 transition-colors text-sm">
                Hubungi Client
            </button>
            
            <button type="button" class="w-full bg-[#5bc0de] hover:bg-[#4eb0ce] text-white font-medium py-3 px-4 rounded-lg shadow-sm transition-colors text-sm">
                Unggah Hasil
            </button>
        </div>
    </div>
</x-app-layout>
