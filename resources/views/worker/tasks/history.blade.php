<x-app-layout>
    <!-- Header Halaman -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Riwayat</h2>
        <p class="text-gray-500 text-sm">Pantau semua riwayat aktivitas pekerjaan</p>
    </div>

    <!-- Container Tabel sebagai Card Utama -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mt-6">
        @if ($tasks->isEmpty())
            <!-- Empty State yang Terpusat & Profesional -->
            <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
                <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mb-4 border border-gray-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Belum ada riwayat pekerjaan</h3>
                <p class="text-sm text-gray-500 max-w-sm">Pekerjaan yang telah selesai dan dibayar oleh client akan muncul di sini.</p>
            </div>
        @else
            <!-- Table Layout dengan Auto-Width, whitespace-nowrap, & Alignment Presisi -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-sm">
                    <thead class="text-xs text-gray-700 bg-gray-50 border-b">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold text-left align-middle whitespace-nowrap">Nama</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-left align-middle whitespace-nowrap">Nama Pekerjaan</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-left align-middle whitespace-nowrap">Kategori</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-left align-middle whitespace-nowrap">Tanggal</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center align-middle whitespace-nowrap">Status</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-left align-middle whitespace-nowrap">Harga</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-left align-middle whitespace-nowrap">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($tasks as $task)
                            <tr class="bg-white hover:bg-gray-50/50 transition-colors duration-150">
                                <!-- Nama Client -->
                                <td class="px-6 py-4 text-left whitespace-nowrap text-gray-600 align-middle">{{ $task->project->client ? ($task->project->client->first_name . ' ' . $task->project->client->last_name) : '-' }}</td>
                                <!-- Nama Pekerjaan / Judul -->
                                <td class="px-6 py-4 text-left whitespace-nowrap text-gray-900 font-medium align-middle">{{ $task->project->title }}</td>
                                <!-- Kategori -->
                                <td class="px-6 py-4 text-left whitespace-nowrap text-gray-500 align-middle">{{ $task->project->category ?? '-' }}</td>
                                <!-- Tanggal Selesai / Pembayaran -->
                                <td class="px-6 py-4 text-left whitespace-nowrap text-gray-500 align-middle">{{ \Carbon\Carbon::parse($task->updated_at)->translatedFormat('j F Y') }}</td>
                                <!-- Status Badge Terpusat Konsisten -->
                                <td class="px-6 py-4 text-center whitespace-nowrap align-middle">
                                    @if ($task->status === 'completed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#e6f9ed] text-[#10b981]">Berhasil</span>
                                    @elseif ($task->status === 'failed')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#fee2e2] text-[#ef4444]">Gagal</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-600">{{ ucfirst($task->status) }}</span>
                                    @endif
                                </td>
                                <!-- Harga Menonjol Rata Kiri -->
                                <td class="px-6 py-4 text-left whitespace-nowrap text-gray-900 font-semibold align-middle">Rp. {{ number_format($task->project->budget, 0, ',', '.') }}</td>
                                <!-- Keterangan Rata Kiri -->
                                <td class="px-6 py-4 text-left whitespace-nowrap text-gray-500 align-middle">@if ($task->status === 'completed') Telah dikerjakan @elseif ($task->status === 'failed') Gagal dikerjakan @else - @endif</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
