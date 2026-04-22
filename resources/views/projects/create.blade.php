<x-app-layout>
    <div class="mb-8">
        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-blue-500 flex items-center mb-4">
            Kembali
        </a>
        <h2 class="text-3xl font-bold text-gray-800 mb-1">Pasang Pekerjaan Baru</h2>
        <p class="text-gray-400 text-sm">Berikan detail spesifik tentang pekerjaan hasil yang diharapkan untuk menghindari revisi.</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800">Detail Pekerjaan</h3>
            <p class="text-gray-500 text-sm">Berikan informasi lengkap agar calon pekerja memahami tugas yang diberikan.</p>
        </div>

        <form action="{{ route('projects.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Judul Pekerjaan -->
                <div class="md:col-span-1">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Pekerjaan</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" 
                        class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori Pekerjaan -->
                <div class="md:col-span-1">
                    <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Kategori Pekerjaan</label>
                    <input type="text" id="category" name="category" value="{{ old('category') }}" 
                        class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Anggaran (Rp) -->
                <div class="md:col-span-1">
                    <label for="budget" class="block text-sm font-semibold text-gray-700 mb-2">Anggaran (Rp)</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp.</span>
                        </div>
                        <input type="number" id="budget" name="budget" value="{{ old('budget') }}" min="0" step="1000"
                            class="w-full border border-gray-300 rounded-md shadow-sm py-2 pl-10 pr-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    @error('budget')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Deskripsi Lengkap -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Lengkap</label>
                    <textarea id="description" name="description" rows="6" 
                        class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-1 flex flex-col justify-between">
                    <!-- Batas Waktu (Deadline) -->
                    <div>
                        <label for="deadline" class="block text-sm font-semibold text-gray-700 mb-2">Batas Waktu (Deadline)</label>
                        <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}" 
                            class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="dd/mm/yyyy">
                        @error('deadline')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Button -->
                    <div class="mt-4">
                        <button type="submit" class="bg-[#5bc0de] text-white py-2 px-6 rounded-md shadow-sm hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-semibold mb-2">
                            Pasang Pekerjaan
                        </button>
                        <p class="text-[10px] text-gray-500">Lowongan akan ditinjau oleh admin sebelum dipublikasikan.</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </form>
    </div>
</x-app-layout>
