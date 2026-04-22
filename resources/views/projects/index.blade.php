<x-app-layout>
    <div class="mb-8">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-blue-500 flex items-center mb-4">
            Kembali
        </a>
        <h2 class="text-3xl font-bold text-gray-800 mb-1">Lowongan Pekerjaan</h2>
        <p class="text-gray-400 text-sm">Temukan pekerjaan yang sesuai dengan keahlian Anda dan mulai hasilkan uang.</p>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 text-red-700 bg-red-100 rounded-lg border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6">
        @forelse($projects as $project)
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center hover:shadow-md transition-shadow">
                <div class="flex-1 mb-4 md:mb-0 pr-0 md:pr-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $project->title }}</h3>
                    <div class="flex flex-wrap items-center text-sm text-gray-500 mb-3 space-x-4">
                        @if($project->category)
                            <span class="bg-blue-50 text-blue-700 border border-blue-200 py-1 px-3 rounded-full text-xs font-medium">{{ $project->category }}</span>
                        @endif
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            {{ $project->client->first_name ?? 'Klien' }} {{ $project->client->last_name ?? '' }}
                        </span>
                        @if($project->deadline)
                            <span class="flex items-center text-red-500 bg-red-50 py-1 px-2 rounded-md">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Deadline: {{ \Carbon\Carbon::parse($project->deadline)->format('d M Y') }}
                            </span>
                        @endif
                    </div>
                    <div class="text-gray-600 text-sm whitespace-pre-line">{{ $project->description }}</div>
                </div>
                
                <div class="flex flex-col items-start md:items-end w-full md:w-auto mt-4 md:mt-0 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6 shrink-0">
                    <div class="text-xs text-gray-500 mb-1 uppercase tracking-wider font-semibold">Anggaran</div>
                    <div class="text-2xl font-bold text-green-600 mb-4">
                        Rp {{ number_format($project->budget ?? 0, 0, ',', '.') }}
                    </div>
                    <form action="{{ route('jobs.take', $project->id) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full md:w-auto bg-[#5bc0de] text-white py-2.5 px-6 rounded-md shadow-sm hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-semibold transition-colors" onclick="return confirm('Apakah Anda yakin ingin mengambil pekerjaan ini? Anda diharapkan untuk menyelesaikannya tepat waktu.')">
                            Ambil Pekerjaan
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="bg-white border border-gray-200 rounded-xl p-12 shadow-sm text-center">
                <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum ada lowongan pekerjaan</h3>
                <p class="text-gray-500 text-sm max-w-md mx-auto">Saat ini belum ada klien yang memposting pekerjaan baru. Silakan periksa kembali nanti untuk menemukan peluang menarik.</p>
            </div>
        @endforelse
    </div>
</x-app-layout>
