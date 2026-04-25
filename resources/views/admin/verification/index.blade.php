<x-app-layout>
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-1">Verifikasi Lowongan</h2>
                <p class="text-gray-500 text-sm">Tinjau dan setujui pekerjaan yang baru diposting sebelum tampil di marketplace.</p>
            </div>
        </div>
    </div>

    <!-- 3 Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Antrean Saat Ini -->
        <div class="bg-gradient-to-br from-white to-blue-50 border border-blue-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow flex items-center">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 text-white rounded-xl flex items-center justify-center mr-5 shadow-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <span class="text-4xl font-extrabold text-blue-900">{{ $pendingCount }}</span>
                <h3 class="text-gray-800 font-bold mt-1">Antrean Saat Ini</h3>
                <p class="text-xs text-blue-600/70 font-medium">Perlu segera ditinjau</p>
            </div>
        </div>

        <!-- Selesai Diverifikasi -->
        <div class="bg-gradient-to-br from-white to-green-50 border border-green-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow flex items-center">
            <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 text-white rounded-xl flex items-center justify-center mr-5 shadow-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <span class="text-4xl font-extrabold text-green-900">{{ $verifiedCount }}</span>
                <h3 class="text-gray-800 font-bold mt-1">Selesai Diverifikasi</h3>
                <p class="text-xs text-green-600/70 font-medium">Total yang disetujui</p>
            </div>
        </div>

        <!-- Ditolak/Spam -->
        <div class="bg-gradient-to-br from-white to-red-50 border border-red-100 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow flex items-center">
            <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-red-600 text-white rounded-xl flex items-center justify-center mr-5 shadow-inner">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <div>
                <span class="text-4xl font-extrabold text-red-900">{{ $rejectedCount }}</span>
                <h3 class="text-gray-800 font-bold mt-1">Ditolak/Spam</h3>
                <p class="text-xs text-red-600/70 font-medium">Kualitas rendah/spam</p>
            </div>
        </div>
    </div>

    <!-- Antrean Peninjauan Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Antrean Peninjauan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 bg-gray-50 border-b">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-semibold">Nama Pekerjaan</th>
                        <th scope="col" class="px-6 py-3 font-semibold">Client</th>
                        <th scope="col" class="px-6 py-3 font-semibold">Kategori</th>
                        <th scope="col" class="px-6 py-3 font-semibold">Harga</th>
                        <th scope="col" class="px-6 py-3 font-semibold">Deadline</th>
                        <th scope="col" class="px-6 py-3 font-semibold text-center">Verifikasi</th>
                        <th scope="col" class="px-6 py-3 font-semibold text-center">Tolak</th>
                        <th scope="col" class="px-6 py-3 font-semibold text-center">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingProjects as $project)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $project->title }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $project->client->first_name }} {{ $project->client->last_name }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $project->category ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-500">Rp. {{ number_format($project->budget, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.verification.verify', $project) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="text-green-500 hover:text-green-700">
                                        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.verification.verify', $project) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg class="w-6 h-6 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-center font-medium">
                                <a href="{{ route('admin.verification.show', $project) }}" class="text-gray-800 hover:text-blue-600">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada antrean peninjauan saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
