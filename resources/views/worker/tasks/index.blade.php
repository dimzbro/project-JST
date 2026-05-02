<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pekerjaan Yang Diambil</h1>
        <p class="text-gray-400 text-sm mt-1">Pantau dan kelola semua pekerjaan yang Anda publikasikan.</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-white">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-800 tracking-wider">
                            Nama Pekerjaan
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-800 tracking-wider">
                            Kategori
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-800 tracking-wider">
                            Harga
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-800 tracking-wider">
                            Deadline
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-800 tracking-wider">
                            Tenggat Waktu
                        </th>
                        <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-gray-800 tracking-wider">
                            Upload Tugas
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($tasks as $task)
                        @php
                            $deadlineDate = \Carbon\Carbon::parse($task->project->deadline);
                            $daysRemaining = max(0, \Carbon\Carbon::now()->startOfDay()->diffInDays($deadlineDate, false));
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $task->project->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $task->project->category ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Rp. {{ number_format($task->project->budget, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($task->project->deadline)->translatedFormat('j F Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ intval($daysRemaining) }}hari
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <button type="button" class="inline-flex items-center justify-center px-4 py-1.5 border border-blue-400 text-blue-500 hover:bg-blue-50 rounded-md text-sm font-medium bg-white transition-colors">
                                    Upload
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                                Belum ada pekerjaan yang diambil.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
