<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('admin.verification.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Detail Pekerjaan</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <!-- Client Info Card -->
            <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm flex justify-between items-center">
                <div class="flex items-center">
                    <img src="{{ asset('path/to/default-avatar.jpg') }}" alt="Client" class="w-12 h-12 rounded-full bg-gray-300 mr-4 object-cover">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">{{ $project->client->first_name }} {{ $project->client->last_name }}</h3>
                        <div class="flex items-center text-gray-500 text-sm">
                            <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                            8.0/10.0
                        </div>
                    </div>
                </div>
                <div class="text-right text-sm">
                    <p class="text-gray-500">Di posting</p>
                    <p class="text-gray-700 font-medium">{{ $project->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <!-- Job Content Card -->
            <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm">
                <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ $project->title }}</h1>
                
                <div class="border border-gray-200 rounded-lg p-6">
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-800">Kategori</h4>
                        <p class="text-gray-500 text-sm">{{ $project->category ?? '-' }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800 mb-2">Deskripsi Pekerjaan</h4>
                        <div class="text-gray-600 text-sm whitespace-pre-line leading-relaxed">
                            {{ $project->description }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Price Card -->
            <div class="bg-white border border-gray-800 rounded-xl p-6 shadow-sm">
                <p class="text-gray-500 text-sm mb-1">Anggaran Proyek</p>
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Rp. {{ number_format($project->budget, 0, ',', '.') }}</h2>
                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Deadline</span>
                        <span class="text-gray-800 font-medium">{{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tenggat</span>
                        <span class="text-gray-800 font-medium">{{ $project->deadline ? (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($project->deadline)->startOfDay()) . ' Hari' : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <form action="{{ route('admin.verification.verify', $project) }}" method="POST" class="mt-6">
                @csrf
                <button type="submit" name="action" value="approve" class="w-full py-3 mb-4 rounded-lg border border-green-400 text-green-500 font-bold hover:bg-green-50 transition-colors bg-white">
                    Verifikasi
                </button>
                <button type="submit" name="action" value="reject" class="w-full py-3 rounded-lg border border-red-400 text-red-500 font-bold hover:bg-red-50 transition-colors bg-white">
                    Tolak
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
