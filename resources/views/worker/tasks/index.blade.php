<x-app-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pekerjaan Yang Diambil</h1>
        <p class="text-gray-400 text-sm mt-1">Pantau dan kelola semua pekerjaan yang Anda publikasikan.</p>
    </div>

    <div class="bg-white rounded-lg border border-gray-300 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="py-4 px-6 text-sm font-semibold text-gray-700">
                            Nama Pekerjaan
                        </th>
                        <th class="py-4 px-6 text-sm font-semibold text-gray-700">
                            Kategori
                        </th>
                        <th class="py-4 px-6 text-sm font-semibold text-gray-700">
                            Harga
                        </th>
                        <th class="py-4 px-6 text-sm font-semibold text-gray-700">
                            Deadline
                        </th>
                        <th class="py-4 px-6 text-sm font-semibold text-gray-700">
                            Tenggat Waktu
                        </th>
                        <th class="py-4 px-6 text-sm font-semibold text-gray-700 text-center">
                            Upload Tugas
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($tasks as $task)
                        @php
                            $deadlineDate = \Carbon\Carbon::parse($task->project->deadline);
                            $daysRemaining = max(0, \Carbon\Carbon::now()->startOfDay()->diffInDays($deadlineDate, false));
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $task->project->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $task->project->category ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                Rp. {{ number_format($task->project->budget, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($task->project->deadline)->translatedFormat('j F Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ intval($daysRemaining) }} hari
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('worker.tasks.show', $task->id) }}" class="inline-flex items-center justify-center px-4 py-1.5 border border-[#3ba8c9] text-[#3ba8c9] hover:bg-[#f0f9fb] rounded-md text-sm font-medium bg-white transition-colors">
                                    Upload
                                </a>
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
