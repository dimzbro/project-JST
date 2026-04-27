<x-app-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Pekerjaan Yang Diambil</h2>
        <p class="text-gray-400 text-sm mt-1">Pantau dan kelola semua pekerjaan yang Anda publikasikan.</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 capitalize tracking-wider w-1/3">
                        Nama Pekerjaan
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 capitalize tracking-wider">
                        Kategori
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 capitalize tracking-wider">
                        Harga
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 capitalize tracking-wider">
                        Deadline
                    </th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 capitalize tracking-wider">
                        Tenggat Waktu
                    </th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 capitalize tracking-wider w-32">
                        Upload Tugas
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($tasks as $task)
                    @php
                        // Hitung tenggat waktu
                        $tenggat_waktu = '-';
                        if ($task->project->deadline) {
                            $deadline = \Carbon\Carbon::parse($task->project->deadline);
                            $now = \Carbon\Carbon::now();
                            if ($deadline->isPast()) {
                                $tenggat_waktu = 'Berakhir';
                            } else {
                                $diff = $now->diffInDays($deadline);
                                $tenggat_waktu = $diff . 'hari';
                            }
                        }
                    @endphp
                    <tr>
                        <td class="px-6 py-5">
                            <div class="text-sm font-medium text-gray-500">{{ $task->project->title }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm text-gray-500">{{ $task->project->category ?? 'Desain Grafis' }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm text-gray-500">
                                {{ $task->project->budget ? 'Rp. ' . number_format($task->project->budget, 0, ',', '.') : 'Rp. 250.000' }}
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm text-gray-500">
                                {{ $task->project->deadline ? \Carbon\Carbon::parse($task->project->deadline)->translatedFormat('j M Y') : '8 Mei 2026' }}
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm text-gray-500">{{ $tenggat_waktu !== '-' ? $tenggat_waktu : '3hari' }}</div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            <button type="button" class="inline-flex items-center justify-center px-4 py-1.5 border border-blue-400 text-sm font-medium rounded-md text-blue-500 bg-white hover:bg-blue-50 focus:outline-none transition-colors">
                                Upload
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                            Belum ada pekerjaan yang diambil.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
