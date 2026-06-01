<x-app-layout>
    <!-- Header Halaman -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Riwayat</h2>
        <p class="text-gray-500 text-sm">Pantau semua riwayat aktivitas pekerjaan</p>
    </div>

    <!-- Container Tabel sebagai Card Utama -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mt-6">
        @if ($projects->isEmpty())
            <!-- Empty State yang Terpusat & Profesional -->
            <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
                <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mb-4 border border-gray-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-1">Belum ada riwayat pekerjaan</h3>
                <p class="text-sm text-gray-500 max-w-sm">Pekerjaan yang telah selesai dan dibayar akan muncul di sini.</p>
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
                        @foreach ($projects as $project)
                            @php
                                // Mengambil task terpilih secara aman untuk mendapatkan data worker terpilih
                                $selectedTask = $project->tasks ? $project->tasks->firstWhere('is_selected', true) : null;
                                $workerName = $selectedTask && $selectedTask->worker 
                                    ? ($selectedTask->worker->first_name . ' ' . $selectedTask->worker->last_name) 
                                    : 'Tidak ditemukan'; // Fallback aman untuk menghindari error jika data worker kosong
                            @endphp
                            <tr class="bg-white hover:bg-gray-50/50 transition-colors duration-150">
                                <!-- Nama Worker Terpilih (Rata Kiri) -->
                                <td class="px-6 py-4 text-left whitespace-nowrap text-gray-600 align-middle">{{ $workerName }}</td>
                                <!-- Nama Pekerjaan / Judul (Rata Kiri) -->
                                <td class="px-6 py-4 text-left whitespace-nowrap text-gray-900 font-medium align-middle">{{ $project->title }}</td>
                                <!-- Kategori (Rata Kiri) -->
                                <td class="px-6 py-4 text-left whitespace-nowrap text-gray-500 align-middle">{{ $project->category ?? '-' }}</td>
                                <!-- Tanggal Selesai / Pembayaran (Rata Kiri) -->
                                <td class="px-6 py-4 text-left whitespace-nowrap text-gray-500 align-middle">{{ \Carbon\Carbon::parse($project->updated_at)->translatedFormat('j F Y') }}</td>
                                <!-- Status Badge Terpusat Konsisten -->
                                <td class="px-6 py-4 text-center whitespace-nowrap align-middle">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-[#e6f9ed] text-[#10b981]">Berhasil</span>
                                </td>
                                <!-- Harga Menonjol Rata Kiri -->
                                <td class="px-6 py-4 text-left whitespace-nowrap text-gray-900 font-semibold align-middle">Rp. {{ number_format($project->budget, 0, ',', '.') }}</td>
                                <!-- Keterangan Rata Kiri -->
                                <td class="px-6 py-4 text-left whitespace-nowrap text-gray-500 align-middle">Telah dibayar</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-app-layout>
