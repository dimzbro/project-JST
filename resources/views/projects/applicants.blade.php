<x-app-layout :hideSidebar="true">
    <style>
        /* Paksa seluruh layar & area utama menggunakan warna #E7F0FF */
        body, main.flex-1, .bg-white {
            background-color: #E7F0FF !important;
        }
        /* Hilangkan padding bawaan layout agar background menyentuh ujung layar */
        main.flex-1 > div.p-4 {
            padding: 0 !important;
        }
        /* Ubah juga warna navbar/header paling atas agar menyatu sempurna tanpa area putih */
        main.flex-1 > header {
            background-color: #E7F0FF !important;
            border-bottom: none !important;
        }
        /* Lindungi agar background card tetap putih */
        .bg-white.rounded-xl {
            background-color: #ffffff !important;
        }
    </style>
    <div style="background-color: #E7F0FF; min-height: calc(100vh - 64px); width: 100%;">
        <div class="max-w-[1200px] mx-auto px-6 lg:px-8 py-10 flex flex-col min-h-full">
            <a href="{{ route('projects.show', $project->id) }}" class="text-sm font-medium text-gray-600 hover:text-blue-600 flex items-center mb-8 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali
            </a>

            <div style="margin-bottom: 1rem;">
                <h2 class="text-2xl font-bold text-gray-800">Worker Yang Melamar</h2>
                <p class="text-sm text-gray-500 mt-1">Pilih Worker untuk mengerjakan pekerjaan yang anda</p>
            </div>

            @if($project->tasks->isEmpty())
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 40vh; width: 100%;">
                    <p class="text-gray-500 text-lg text-center font-medium">Belum ada pelamar untuk pekerjaan ini.</p>
                </div>
            @else
                <style>
                    .applicants-grid {
                        display: grid;
                        grid-template-columns: 1fr;
                        gap: 1.25rem;
                        width: 100%;
                        margin-top: 0.75rem;
                    }
                    @media (min-width: 768px) {
                        .applicants-grid {
                            grid-template-columns: repeat(2, 1fr);
                        }
                    }
                    @media (min-width: 1024px) {
                        .applicants-grid {
                            grid-template-columns: repeat(3, 1fr);
                            gap: 1.5rem;
                        }
                    }
                </style>
                <!-- Grid untuk 3 kolom sejajar secara absolut -->
                <div class="applicants-grid">
                    @foreach($project->tasks as $task)
                        @php
                            $worker = $task->worker;
                        @endphp
                        <div class="bg-white rounded-xl shadow border border-gray-200 overflow-hidden" style="display: flex; flex-direction: column; height: 100%; min-height: 460px;">
                            <div style="padding: 1.75rem 1.25rem 0 1.25rem; flex-grow: 1; display: flex; flex-direction: column;">
                                <!-- Header: Avatar, Name, Rating -->
                                <div class="flex items-center space-x-3" style="margin-bottom: 1rem;">
                                    <div class="bg-gray-300 rounded-full overflow-hidden flex-shrink-0" style="width: 2.75rem; height: 2.75rem;">
                                        @if($worker->profile_photo_path)
                                            <img src="{{ Storage::url($worker->profile_photo_path) }}" alt="Profile" class="w-full h-full object-cover">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($worker->first_name . ' ' . $worker->last_name) }}&color=7F9CF5&background=EBF4FF" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-base leading-tight">
                                            {{ $worker->first_name }} {{ $worker->last_name }}
                                            @if(!$worker->is_active)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-red-100 text-red-800 text-[10px] font-semibold ml-1">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </h3>
                                        <div class="flex items-center text-xs text-gray-500 mt-0.5">
                                            <svg class="w-3.5 h-3.5 text-gray-400 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            {{ $worker->average_rating }}/10.0
                                        </div>
                                    </div>
                                </div>

                                <!-- Tentang Saya -->
                                <div style="margin-bottom: 1rem;">
                                    <h4 class="text-xs font-bold text-gray-900 mb-1">Tentang Saya</h4>
                                    <p class="text-gray-500 leading-snug" style="font-size: 11px; display: -webkit-box; -webkit-line-clamp: 7; -webkit-box-orient: vertical; overflow: hidden; height: 6.5rem;">
                                        {{ $worker->about_me ?: 'Belum ada deskripsi tentang saya.' }}
                                    </p>
                                </div>

                                <!-- Keahlian & Kontak Grid -->
                                <div class="grid grid-cols-2 gap-3" style="margin-bottom: 1.25rem;">
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-900 mb-1.5">Keahlian (Skill)</h4>
                                        <ul class="text-gray-500 list-disc list-inside space-y-0.5" style="font-size: 11px;">
                                            @if($worker->skills)
                                                @php
                                                    $skills = array_map('trim', explode(',', $worker->skills));
                                                @endphp
                                                @foreach(array_slice($skills, 0, 12) as $skill)
                                                    <li>{{ $skill }}</li>
                                                @endforeach
                                                @if(count($skills) > 12)
                                                    <li>...dan {{ count($skills) - 12 }} lainnya</li>
                                                @endif
                                            @else
                                                <li>Belum ada keahlian</li>
                                            @endif
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-900 mb-1.5">Kontak</h4>
                                        <div class="text-gray-500 space-y-0.5" style="font-size: 11px;">
                                            <p>{{ $worker->email }}</p>
                                            <p>{{ $worker->phone_number ?: '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons Footer -->
                            <div style="display: flex; gap: 0.5rem; padding: 0 1.25rem 1.25rem 1.25rem; margin-top: auto;">
                                <a href="{{ route('client.chat.initiate', $task->id) }}" class="flex-1 flex items-center justify-center py-1.5 px-2 border border-gray-800 text-xs font-bold text-gray-800 hover:bg-gray-50 transition-colors" style="border-radius: 0.4rem; text-align: center;">
                                    Hubungi Worker
                                </a>
                                @if($project->status === 'in_progress' || $project->status === 'completed')
                                    <button type="button" disabled class="flex-1 flex-shrink-0 flex items-center justify-center py-1.5 px-2 text-white text-xs font-bold transition-colors opacity-75 cursor-not-allowed" style="border-radius: 0.4rem; background-color: #5cb3ff; border: none;">
                                        Terpilih
                                    </button>
                                @elseif(!$worker->is_active)
                                    <button type="button" disabled class="flex-1 flex-shrink-0 flex items-center justify-center py-1.5 px-2 text-gray-400 text-xs font-bold transition-colors cursor-not-allowed" style="border-radius: 0.4rem; background-color: #e5e7eb; border: none;">
                                        Worker Nonaktif
                                    </button>
                                @else
                                    <form method="POST" action="{{ route('client.tasks.select', $task->id) }}" style="flex: 1; margin: 0; display: flex;">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center py-1.5 px-2 text-white text-xs font-bold transition-colors" style="border-radius: 0.4rem; background-color: #5cb3ff; cursor: pointer; border: none;">
                                            Pilih Worker
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
