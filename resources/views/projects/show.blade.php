<x-app-layout>
    <div class="-m-8 p-8 min-h-full" style="min-height: calc(100vh - 64px); background-color: #ebf4ff;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <a href="{{ route('projects.manage') }}" class="text-xs font-medium text-gray-700 hover:text-blue-600 flex items-center mb-6">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali
            </a>
            
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Detail Pekerjaan</h2>

            <div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
                <!-- Left Column (Informasi Orang & Deskripsi Pekerjaan) -->
                <div class="space-y-6" style="flex: 1 1 65%; min-width: 300px; max-width: 100%;">
                    
                    <!-- Top Profile Card -->
                    <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex justify-between items-center" style="margin-bottom: 1.5rem;">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gray-300 rounded-full overflow-hidden flex-shrink-0">
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="Profile" class="w-full h-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->first_name . ' ' . Auth::user()->last_name) }}&color=7F9CF5&background=EBF4FF" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h3>
                                <div class="flex items-center text-sm text-gray-500 mt-1">
                                    <svg class="w-4 h-4 text-gray-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    0.0/10.0
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            @if($project->status === 'active' || $project->status === 'completed')
                                <span class="px-4 py-1 rounded-full text-xs font-bold bg-green-200 text-green-700 inline-block mb-1">Berhasil</span>
                            @elseif($project->status === 'taken')
                                <span class="px-4 py-1 rounded-full text-xs font-bold bg-blue-200 text-blue-700 inline-block mb-1">Dalam Proses</span>
                            @elseif($project->status === 'pending')
                                <span class="px-4 py-1 rounded-full text-xs font-bold bg-gray-300 text-gray-700 inline-block mb-1">Menunggu</span>
                            @elseif($project->status === 'rejected')
                                <span class="px-4 py-1 rounded-full text-xs font-bold bg-red-200 text-red-700 inline-block mb-1">Ditolak</span>
                            @else
                                <span class="px-4 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-700 inline-block mb-1">{{ ucfirst($project->status) }}</span>
                            @endif
                            <div class="text-xs text-gray-500">
                                @if($project->deadline)
                                    Sisa waktu: {{ max(0, (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($project->deadline)->startOfDay(), false)) }} hari lagi
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Description Card -->
                    <div class="bg-white rounded-xl p-6 shadow-sm">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $project->title }}</h2>
                        
                        <div class="border border-gray-300 rounded-lg p-5">
                            <div style="margin-bottom: 1.5rem;">
                                <h4 class="text-sm font-bold text-gray-800">Kategori</h4>
                                <p class="text-xs text-gray-400 mt-1">{{ $project->category ?? 'Tidak ada kategori' }}</p>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-bold text-gray-800">Deskripsi Pekerjaan</h4>
                                <div class="text-xs text-gray-400 mt-2 leading-relaxed whitespace-pre-wrap">{{ $project->description }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column (Anggaran, Edit, Hapus) -->
                <div style="flex: 0 0 280px; width: 280px; max-width: 100%;">
                    <div class="bg-white border border-gray-800 rounded-lg p-5 shadow-sm mb-4">
                        <p class="text-xs text-gray-500 font-medium mb-1">Anggaran Proyek</p>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Rp. {{ number_format($project->budget, 0, ',', '.') }}</h3>
                        
                        <div class="flex justify-between items-center text-xs text-gray-500 mb-2">
                            <span>Deadline</span>
                            <span>{{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs text-gray-500 mb-5">
                            <span>Tenggat</span>
                            <span>{{ $project->deadline ? max(0, (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($project->deadline)->startOfDay(), false)) . ' Hari' : '-' }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center text-xs text-gray-500 border-t border-gray-300 pt-3">
                            <span>Pelamar</span>
                            <div class="flex items-center space-x-2">
                                <span>{{ $project->tasks ? $project->tasks->count() : 0 }}</span>
                                <!-- TODO: link to applicant details modal or page -->
                                <a href="#" class="text-blue-500 font-medium hover:underline">Lihat</a>
                            </div>
                        </div>
                    </div>

                    @if(in_array($project->status, ['pending', 'rejected']))
                        <a href="{{ route('projects.edit', $project->id) }}" class="block w-full text-center bg-white border border-gray-800 text-gray-800 font-bold rounded-md py-2 shadow-sm hover:bg-gray-50 transition-colors mb-4">
                            Edit
                        </a>
                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pekerjaan ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="block w-full text-center bg-white border border-red-400 text-red-500 font-bold rounded-md py-2 shadow-sm hover:bg-red-50 transition-colors">
                                Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
