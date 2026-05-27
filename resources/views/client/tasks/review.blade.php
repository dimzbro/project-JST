<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('projects.manage') }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Kembali</a>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">Review Hasil Pekerjaan</h2>
    </div>

    @php
        $deadlineDate = \Carbon\Carbon::parse($task->project->deadline);
        $daysRemaining = round(\Carbon\Carbon::now()->startOfDay()->diffInDays($deadlineDate->startOfDay(), false));
        $tenggatWaktuText = $daysRemaining < 0 ? abs($daysRemaining) . ' Hari Terlambat' : ($daysRemaining === 0 ? 'Hari Ini' : $daysRemaining . ' Hari');
    @endphp

    @if(session('success'))
        @if(str_contains(session('success'), 'Pekerjaan telah diterima'))
            <!-- Modal Pembayaran Berhasil -->
            <div id="paymentSuccessModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.4);">
                <div class="bg-white rounded-2xl shadow-xl w-full flex flex-col items-center justify-center relative py-12 px-8" style="max-width: 450px; min-height: 320px;">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Pembayaran Berhasil</h2>
                    
                    <div class="w-24 h-24 rounded-full text-white flex items-center justify-center shadow-sm mb-2" style="background-color: #22c55e;">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    
                    <div class="absolute" style="bottom: 24px; left: 32px;">
                        <button onclick="document.getElementById('paymentSuccessModal').style.display='none'" class="text-xs font-medium text-gray-500 hover:text-gray-800 transition">Kembali</button>
                    </div>
                </div>
            </div>
        @else
            <!-- Regular Success Alert -->
            <div style="margin-bottom: 1rem; padding: 0.75rem 1rem; background-color: #d1fae5; color: #065f46; border-radius: 0.5rem; font-size: 0.875rem;">
                {{ session('success') }}
            </div>
        @endif
    @endif

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Kolom Kiri -->
        <div class="w-full lg:w-2/3 flex flex-col gap-6">
            
            <!-- Worker Profile Card -->
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-200">
                        <img src="{{ $task->worker->profile_photo_path ? Storage::url($task->worker->profile_photo_path) : 'https://ui-avatars.com/api/?name='.urlencode($task->worker->first_name . ' ' . $task->worker->last_name) }}" alt="Profile" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $task->worker->first_name }} {{ $task->worker->last_name }}</h3>
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            <span>{{ number_format($task->worker->average_rating ?? 0.0, 1) }}/10.0</span>
                        </div>
                    </div>
                </div>
                <div class="text-right text-xs text-gray-500">
                    <p>Dikirim</p>
                    <p class="font-semibold text-gray-700" title="{{ $task->updated_at->translatedFormat('d M Y, H:i') }} WIB">
                        {{ $task->updated_at->diffForHumans() }}
                    </p>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h3 class="font-bold text-gray-800 mb-4">Catatan Pekerjaan</h3>
                <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-wrap">{{ $task->notes ?: 'Tidak ada catatan.' }}</div>
            </div>

            <!-- Uploaded Files Section -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h3 class="font-bold text-gray-800 mb-4">Hasil Pekerjaan</h3>
                
                <div class="space-y-3 mb-6">
                    @forelse($uploadedFiles as $file)
                    @php
                        $fileUrl = Storage::url($file['path']);
                        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    @endphp
                    <div onclick="openPreviewModal('{{ $fileUrl }}', '{{ $ext }}', '{{ $file['name'] }}')" class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:border-[#5bc0de] hover:shadow-sm transition cursor-pointer group bg-gray-50/30">
                        <div class="flex items-center gap-3 overflow-hidden">
                            <div class="text-gray-400 group-hover:text-[#5bc0de] transition flex-shrink-0">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <div class="truncate">
                                <p class="text-sm font-medium text-gray-800 group-hover:text-blue-600 transition truncate">{{ $file['name'] ?? 'File' }}</p>
                                <p class="text-xs text-gray-500">{{ isset($file['size']) ? number_format($file['size'] / 1024 / 1024, 1) : 0 }} MB</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ml-4">
                            @if($task->status === 'completed')
                                <a href="{{ $fileUrl }}" download="{{ $file['name'] }}" onclick="event.stopPropagation()" class="text-xs bg-[#5bc0de] text-white hover:bg-[#4ab0ce] px-3 py-1.5 rounded font-medium transition whitespace-nowrap shadow-sm">Download</a>
                            @else
                                <span class="text-xs bg-[#5bc0de] text-white hover:bg-[#4ab0ce] px-3 py-1.5 rounded font-medium transition whitespace-nowrap shadow-sm">Lihat Preview</span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">Belum ada file yang diunggah.</p>
                    @endforelse
                </div>

                @if($task->status === 'completed')
                <div class="flex items-start gap-2 text-xs text-green-600 pt-4 border-t border-gray-100 font-medium">
                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Pembayaran selesai! Anda sekarang dapat mengunduh file hasil pekerjaan secara penuh.</span>
                </div>
                @else
                <div class="flex items-start gap-2 text-xs text-gray-500 pt-4 border-t border-gray-100">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Selesaikan pembayaran terlebih dahulu agar bisa mendownload hasil pekerjaan</span>
                </div>
                @endif
            </div>
            
        </div>

        <!-- Kolom Kanan -->
        <div class="w-full lg:w-1/3">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm sticky top-6">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4">Ringkasan Pekerjaan</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="mb-4">
                            <p class="text-gray-500 text-xs mb-1">Judul Pekerjaan</p>
                            <p class="font-medium text-gray-800">{{ $task->project->title }}</p>
                        </div>
                        
                        <div class="flex justify-between items-center py-1">
                            <span class="text-gray-500">Kategori</span>
                            <span class="text-gray-800">{{ $task->project->category ?? 'Desain Grafis' }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-1">
                            <span class="text-gray-500">Pekerja</span>
                            <span class="text-gray-800">{{ $task->worker->first_name }} {{ $task->worker->last_name }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-1">
                            <span class="text-gray-500">Deadline</span>
                            <span class="text-gray-800">{{ \Carbon\Carbon::parse($task->project->deadline)->translatedFormat('d M Y') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-1">
                            <span class="text-gray-500">Tenggat Waktu</span>
                            <span class="text-gray-800">{{ $tenggatWaktuText }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="p-5 bg-gray-50/50">
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between items-center text-gray-500">
                            <span>Biaya Jasa Dasar</span>
                            <span>Rp. {{ number_format($biayaJasaDasar, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-500">
                            <span>Biaya Layanan Platform</span>
                            <span>Rp. {{ number_format($biayaLayananPlatform, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center font-bold text-lg pt-4 border-t border-gray-200">
                        <span class="text-gray-800">{{ $task->status === 'completed' ? 'Berhasil Dibayar' : 'Total Tagihan' }}</span>
                        <span class="text-[#5bc0de]">Rp. {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($task->status !== 'completed')
            <div class="mt-6">
                <form id="payForm" action="{{ route('client.tasks.accept', $task->id) }}" method="POST">
                    @csrf
                    <button type="button" onclick="openPayModal()" class="w-full bg-[#5bc0de] hover:bg-[#4eb0ce] text-white font-semibold py-3 px-4 rounded-lg shadow-sm transition text-center">
                        Bayar
                    </button>
                </form>
                
                <form id="reviseForm" action="{{ route('client.tasks.revise', $task->id) }}" method="POST" class="mt-4">
                    @csrf
                    <button type="button" onclick="openReviseModal()" class="w-full bg-white border border-red-300 text-red-500 hover:bg-red-50 font-semibold py-3 px-4 rounded-lg shadow-sm transition text-center">
                        Minta Revisi
                    </button>
                </form>
            </div>
            @else
                @if(is_null($task->rating))
                <div class="mt-6">
                    <button type="button" id="btn-beri-rating" class="w-full font-semibold transition text-center" style="display: block; width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 12px; font-weight: 600; text-align: center; color: #1f2937; background-color: #fff; cursor: pointer; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                        Beri Rating
                    </button>
                    
                    <div id="rating-form-container" class="mt-6 text-center" style="display: none;">
                        <!-- Star Icons (10 Stars) -->
                        <div class="flex justify-between items-center mb-3" id="star-rating-container" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; width: 100%;">
                            @for($i = 1; $i <= 10; $i++)
                                <button type="button" data-value="{{ $i }}" class="star-btn hover:scale-110 transition-transform" style="background: none; border: none; padding: 0; cursor: pointer;">
                                    <svg class="star-svg" style="width: 28px; height: 28px; transition: transform 0.1s;" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499c.198-.39.754-.39.952 0l2.253 4.437 5.03 1.054c.451.095.632.628.324.957l-3.528 3.513 1.002 5.093c.09.458-.415.807-.81.603L12 17.905l-4.71 2.251c-.394.204-.9-.145-.81-.603l1.002-5.093-3.528-3.513c-.308-.329-.127-.862.324-.957l5.03-1.054 2.253-4.437z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        
                        <!-- Rating Helper Text -->
                        <p id="rating-helper-text" class="text-xs text-gray-500 mb-2 leading-relaxed" style="display: block; font-size: 0.75rem; color: #6b7280; line-height: 1.625; margin-bottom: 8px;">
                            Berikan rating worker yang telah mengerjakan pekerjaan Anda.
                        </p>

                        <!-- Rating Action Form -->
                        <form id="rating-submit-form" action="{{ route('client.tasks.rate', $task->id) }}" method="POST" style="display: none;">
                            @csrf
                            <input type="hidden" name="rating" id="selected-rating-value" value="">

                            <!-- Textarea Ulasan -->
                            <div id="review-textarea-container" style="margin-top: 12px; text-align: left;">
                                <label for="review-text" style="display: block; font-size: 0.75rem; font-weight: 600; color: #374151; margin-bottom: 6px;">Ulasan Anda <span style="color:#9ca3af; font-weight:400;">(opsional)</span></label>
                                <textarea id="review-text" name="review" rows="3" placeholder="Bagaimana kinerja worker? Apakah hasil memuaskan? Apakah pengerjaan cepat?" style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 10px; font-size: 0.8125rem; color: #374151; resize: vertical; outline: none; font-family: inherit; line-height: 1.5; box-sizing: border-box;" onfocus="this.style.borderColor='#5bc0de'" onblur="this.style.borderColor='#d1d5db'"></textarea>
                            </div>
                            
                            <div class="flex items-center justify-between gap-4 mt-2" style="display: flex; gap: 16px; margin-top: 10px;">
                                <button type="button" id="btn-cancel-rating" class="flex-1 font-medium transition" style="flex: 1; padding: 10px; border-radius: 12px; border: 1px solid #d1d5db; color: #374151; background-color: #fff; cursor: pointer; text-align: center;">
                                    Batal
                                </button>
                                <button type="submit" class="flex-1 font-medium transition" style="flex: 1; padding: 10px; border-radius: 12px; border: none; color: #fff; background-color: #5bc0de; cursor: pointer; text-align: center;">
                                    Kirim Rating
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @else
                <div class="mt-6 p-4 bg-green-50 text-green-700 rounded-lg border border-green-200 text-center font-medium">
                    Pekerjaan telah diselesaikan dan dibayar.
                </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-80 flex items-center justify-center p-4" style="backdrop-filter: blur(4px);">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-5xl flex flex-col overflow-hidden relative" style="max-height: 95vh;">
            <div class="p-4 border-b flex justify-between items-center bg-gray-50 flex-shrink-0">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <h3 id="previewTitle" class="font-bold text-gray-800 truncate pr-4">Preview File</h3>
                </div>
                <button onclick="closePreviewModal()" class="text-gray-400 hover:text-red-500 bg-white hover:bg-red-50 rounded-full p-1 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div id="previewContent" class="flex-1 min-h-0 overflow-y-auto bg-gray-200 relative p-4" oncontextmenu="return false;">
                <!-- Content injected here -->
            </div>
        </div>
    </div>

    <!-- Pay Confirmation Modal -->
    <div id="payModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.4);">
        <div class="bg-white rounded-2xl shadow-xl w-full p-8 text-center" style="max-width: 400px;">
            <div class="w-16 h-16 rounded-full bg-[#e0f7fa] text-[#5bc0de] mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-2">Konfirmasi Pembayaran</h3>
            <p class="text-gray-500 text-sm mb-8">Apakah Anda yakin ingin membayar dan menyelesaikan pekerjaan ini?</p>
            
            <div class="flex items-center justify-center gap-4">
                <button onclick="closePayModal()" class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 font-medium transition">Tidak</button>
                <button onclick="submitPayForm()" class="flex-1 py-2.5 rounded-xl bg-[#5bc0de] hover:bg-[#4ab0ce] text-white font-medium transition">Ya, Bayar</button>
            </div>
        </div>
    </div>

    <!-- Revise Confirmation Modal -->
    <div id="reviseModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" style="background-color: rgba(0, 0, 0, 0.4);">
        <div class="bg-white rounded-2xl shadow-xl w-full p-8 text-center" style="max-width: 400px;">
            <div class="w-16 h-16 rounded-full bg-[#e0f7fa] text-[#5bc0de] mx-auto flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-2">Konfirmasi</h3>
            <p class="text-gray-500 text-sm mb-8">Apakah Anda yakin ingin meminta revisi untuk pekerjaan ini?</p>
            
            <div class="flex items-center justify-center gap-4">
                <button onclick="closeReviseModal()" class="flex-1 py-2.5 rounded-xl border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 font-medium transition">Batal</button>
                <button onclick="submitReviseForm()" class="flex-1 py-2.5 rounded-xl bg-[#5bc0de] hover:bg-[#4ab0ce] text-white font-medium transition">Ya, Minta Revisi</button>
            </div>
        </div>
    </div>

    <!-- PDF.js for secure PDF rendering -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <!-- Mammoth for DOCX -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js"></script>
    <!-- SheetJS for Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    
    <style>
        /* Styles for Excel Table rendering */
        .excel-table { width: 100%; border-collapse: collapse; margin: 10px 0; background-color: white; }
        .excel-table th, .excel-table td { border: 1px solid #d1d5db; padding: 6px 12px; text-align: left; }
        .excel-table th { background-color: #f3f4f6; font-weight: bold; }
        /* Styles for DOCX rendering */
        .docx-content { background-color: white; padding: 2rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .docx-content p { margin-bottom: 1rem; }
        .docx-content h1, .docx-content h2, .docx-content h3 { font-weight: bold; margin-bottom: 1rem; margin-top: 1.5rem; }
        .docx-content table { border-collapse: collapse; width: 100%; margin-bottom: 1rem; }
        .docx-content table td, .docx-content table th { border: 1px solid #ccc; padding: 0.5rem; }
    </style>

    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        
        function openPreviewModal(url, ext, name) {
            document.getElementById('previewModal').classList.remove('hidden');
            document.getElementById('previewTitle').innerText = name;
            
            const content = document.getElementById('previewContent');
            content.innerHTML = ''; 
            
            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                content.innerHTML = `
                    <div class="relative w-full min-h-full flex items-center justify-center">
                        <img src="${url}" class="max-w-full max-h-[80vh] object-contain shadow-md rounded" style="pointer-events: none; user-select: none;" ondragstart="return false;" />
                        <div class="absolute inset-0 bg-transparent z-10" title="Preview Only" oncontextmenu="return false;"></div>
                    </div>
                `;
            } else if (ext === 'pdf') {
                content.innerHTML = '<div class="flex items-center justify-center w-full min-h-full"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#5bc0de]"></div><span class="ml-3 text-gray-500">Memuat dokumen...</span></div>';
                
                const pdfContainer = document.createElement('div');
                pdfContainer.className = 'flex flex-col items-center w-full max-w-4xl mx-auto pb-8';
                
                pdfjsLib.getDocument(url).promise.then(function(pdf) {
                    content.innerHTML = '';
                    content.appendChild(pdfContainer);
                    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                        pdf.getPage(pageNum).then(function(page) {
                            const scale = 1.5;
                            const viewport = page.getViewport({ scale: scale });
                            const wrapper = document.createElement('div');
                            wrapper.className = 'mb-6 relative shadow-md bg-white flex justify-center';
                            wrapper.style.order = pageNum;
                            const canvas = document.createElement('canvas');
                            const context = canvas.getContext('2d');
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;
                            canvas.className = 'max-w-full h-auto';
                            wrapper.appendChild(canvas);
                            const overlay = document.createElement('div');
                            overlay.className = 'absolute inset-0 z-10 bg-transparent';
                            overlay.oncontextmenu = function(e) { e.preventDefault(); return false; };
                            wrapper.appendChild(overlay);
                            pdfContainer.appendChild(wrapper);
                            page.render({ canvasContext: context, viewport: viewport });
                        });
                    }
                }).catch(function(error) {
                    content.innerHTML = '<div class="text-center p-8 text-red-500">Gagal memuat PDF</div>';
                });
            } else if (ext === 'docx') {
                content.innerHTML = '<div class="flex items-center justify-center w-full min-h-full"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#5bc0de]"></div><span class="ml-3 text-gray-500">Memuat Word Document...</span></div>';
                fetch(url)
                    .then(response => response.arrayBuffer())
                    .then(arrayBuffer => mammoth.convertToHtml({arrayBuffer: arrayBuffer}))
                    .then(result => {
                        content.innerHTML = `
                            <div class="w-full max-w-4xl mx-auto min-h-[50vh] docx-content relative" oncontextmenu="return false;" style="user-select: none;">
                                ${result.value}
                                <div class="absolute inset-0 z-10 bg-transparent"></div>
                            </div>
                        `;
                    })
                    .catch(err => {
                        content.innerHTML = '<div class="text-center p-8 text-red-500">Gagal memuat file Word. (Note: format lama .doc tidak didukung preview lokal)</div>';
                    });
            } else if (['xls', 'xlsx', 'csv'].includes(ext)) {
                content.innerHTML = '<div class="flex items-center justify-center w-full min-h-full"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#5bc0de]"></div><span class="ml-3 text-gray-500">Memuat Excel...</span></div>';
                fetch(url)
                    .then(response => response.arrayBuffer())
                    .then(data => {
                        const workbook = XLSX.read(data, {type: 'array'});
                        const firstSheet = workbook.SheetNames[0];
                        const worksheet = workbook.Sheets[firstSheet];
                        let html = XLSX.utils.sheet_to_html(worksheet);
                        // Add some classes to the generated table
                        html = html.replace(/<table/g, '<table class="excel-table"');
                        content.innerHTML = `
                            <div class="w-full max-w-5xl mx-auto h-full min-h-[50vh] p-4 bg-white shadow-md rounded-lg overflow-auto relative" oncontextmenu="return false;" style="user-select: none;">
                                <div class="font-bold text-lg mb-4 text-gray-700 border-b pb-2">Sheet: ${firstSheet}</div>
                                ${html}
                                <div class="absolute inset-0 z-10 bg-transparent"></div>
                            </div>
                        `;
                    })
                    .catch(err => {
                        content.innerHTML = '<div class="text-center p-8 text-red-500">Gagal memuat file Excel.</div>';
                    });
            } else if (['txt', 'json', 'md', 'html', 'css', 'js', 'php'].includes(ext)) {
                content.innerHTML = '<div class="flex items-center justify-center w-full min-h-full"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#5bc0de]"></div><span class="ml-3 text-gray-500">Memuat teks...</span></div>';
                fetch(url)
                    .then(response => response.text())
                    .then(text => {
                        content.innerHTML = `
                            <div class="w-full max-w-4xl mx-auto h-full min-h-[50vh] p-6 bg-white shadow-md rounded-lg overflow-auto relative" oncontextmenu="return false;">
                                <pre class="text-sm text-gray-800 whitespace-pre-wrap" style="user-select: none;">${text.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</pre>
                                <div class="absolute inset-0 z-10 bg-transparent"></div>
                            </div>
                        `;
                    });
            } else if (['zip', 'rar', '7z', 'tar', 'gz'].includes(ext)) {
                content.innerHTML = `
                    <div class="text-center p-8 bg-white rounded-lg shadow-sm border border-gray-200">
                        <svg class="w-16 h-16 text-purple-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        <p class="text-gray-800 font-bold mb-1">File Arsip Terenkripsi (${ext.toUpperCase()})</p>
                        <p class="text-sm text-gray-500 max-w-md mx-auto">Demi keamanan sistem, file arsip yang diunggah worker tidak dapat diekstrak atau ditampilkan secara otomatis di browser. Harap selesaikan pembayaran untuk mengunduh dan mengekstrak isinya secara lokal di komputer Anda.</p>
                    </div>
                `;
            } else {
                content.innerHTML = `
                    <div class="text-center p-8 bg-white rounded-lg shadow-sm border border-gray-200">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-gray-800 font-medium mb-1">Preview tidak tersedia</p>
                        <p class="text-sm text-gray-500 max-w-md mx-auto">Format file (.${ext}) ini tidak mendukung preview langsung secara aman. Silakan selesaikan pembayaran untuk mendownload file aslinya.</p>
                    </div>
                `;
            }
        }
        
        function closePreviewModal() {
            document.getElementById('previewModal').classList.add('hidden');
            document.getElementById('previewContent').innerHTML = '';
        }

        // Pay Modal Functions
        function openPayModal() {
            document.getElementById('payModal').classList.remove('hidden');
        }
        
        function closePayModal() {
            document.getElementById('payModal').classList.add('hidden');
        }
        
        function submitPayForm() {
            document.getElementById('payForm').submit();
        }

        // Revise Modal Functions
        function openReviseModal() {
            document.getElementById('reviseModal').classList.remove('hidden');
        }
        
        function closeReviseModal() {
            document.getElementById('reviseModal').classList.add('hidden');
        }
        
        function submitReviseForm() {
            document.getElementById('reviseForm').submit();
        }

        // Rating Star Interactions
        document.addEventListener('DOMContentLoaded', function () {
            const btnBeriRating = document.getElementById('btn-beri-rating');
            const ratingFormContainer = document.getElementById('rating-form-container');
            const starBtns = document.querySelectorAll('.star-btn');
            const ratingHelperText = document.getElementById('rating-helper-text');
            const ratingSubmitForm = document.getElementById('rating-submit-form');
            const selectedRatingValue = document.getElementById('selected-rating-value');
            const btnCancelRating = document.getElementById('btn-cancel-rating');
            
            let currentRating = 0;

            if (btnBeriRating) {
                btnBeriRating.addEventListener('click', function () {
                    btnBeriRating.style.display = 'none';
                    ratingFormContainer.style.display = 'block';
                });
            }

            if (btnCancelRating) {
                btnCancelRating.addEventListener('click', function () {
                    ratingFormContainer.style.display = 'none';
                    btnBeriRating.style.display = 'block';
                    resetStars();
                    currentRating = 0;
                    selectedRatingValue.value = '';
                    // Reset textarea ulasan
                    const reviewText = document.getElementById('review-text');
                    if (reviewText) reviewText.value = '';
                    ratingHelperText.style.display = 'block';
                    ratingSubmitForm.style.display = 'none';
                });
            }

            starBtns.forEach(btn => {
                btn.addEventListener('mouseover', function () {
                    const hoverValue = parseInt(this.getAttribute('data-value'));
                    highlightStars(hoverValue);
                });

                btn.addEventListener('mouseout', function () {
                    highlightStars(currentRating);
                });

                btn.addEventListener('click', function () {
                    currentRating = parseInt(this.getAttribute('data-value'));
                    selectedRatingValue.value = currentRating;
                    highlightStars(currentRating);
                    ratingHelperText.style.display = 'none';
                    ratingSubmitForm.style.display = 'block';
                    // Tampilkan textarea ulasan
                    const reviewContainer = document.getElementById('review-textarea-container');
                    if (reviewContainer) reviewContainer.style.display = 'block';
                });
            });

            function highlightStars(count) {
                starBtns.forEach(btn => {
                    const val = parseInt(btn.getAttribute('data-value'));
                    const svg = btn.querySelector('.star-svg');
                    if (svg) {
                        if (val <= count) {
                            svg.setAttribute('fill', '#facc15'); // Yellow filled color
                            svg.setAttribute('stroke', '#facc15');
                        } else {
                            svg.setAttribute('fill', 'none');
                            svg.setAttribute('stroke', '#d1d5db'); // Gray stroke color
                        }
                    }
                });
            }

            function resetStars() {
                highlightStars(0);
            }
        });
    </script>
</x-app-layout>
