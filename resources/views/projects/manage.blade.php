<x-app-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Kelola Pekerjaan</h2>
        <p class="text-gray-500 text-sm">Pantau dan kelola semua pekerjaan yang Anda publikasikan.</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mt-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-700 bg-gray-50 border-b">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold">Nama Pekerjaan</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Kategori</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Harga</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Deadline</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center">Pelamar</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center">Status</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center">Edit</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center">Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <a href="{{ route('projects.show', $project->id) }}" class="hover:text-blue-600 hover:underline">
                                    {{ $project->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $project->category ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-500">Rp. {{ number_format($project->budget, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('d M Y') : '-' }}</td>
                            <td class="px-6 py-4 text-center text-gray-500">{{ $project->tasks ? $project->tasks->count() : 0 }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($project->status === 'active' || $project->status === 'taken')
                                    <span class="px-4 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Berhasil</span>
                                @elseif($project->status === 'in_progress')
                                    <span class="px-4 py-1 rounded-full text-xs font-medium" style="background-color: #e0f2fe; color: #0ea5e9;">Dalam Proses</span>
                                @elseif($project->status === 'completed')
                                    <span class="px-4 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Selesai</span>
                                @elseif($project->status === 'pending')
                                    <span class="px-4 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-600">Menunggu</span>
                                @elseif($project->status === 'rejected')
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-500">Ditolak</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($project->status) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(in_array($project->status, ['pending', 'rejected']))
                                    <a href="{{ route('projects.edit', $project->id) }}" class="text-gray-800 hover:text-gray-600 block">
                                        <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                    </a>
                                @else
                                    <span class="text-gray-300 block cursor-not-allowed" title="Pekerjaan yang sudah diproses tidak dapat diedit">
                                        <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if(in_array($project->status, ['pending', 'rejected']))
                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pekerjaan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="text-gray-300 cursor-not-allowed" disabled title="Pekerjaan yang sudah diproses tidak dapat dihapus">
                                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">Belum ada pekerjaan yang dipublikasikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
