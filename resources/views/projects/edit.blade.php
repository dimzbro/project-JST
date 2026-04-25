<x-app-layout>
    <div class="min-h-full flex flex-col -m-8 p-8" style="background-color: #e6f0fa; min-height: calc(100vh - 64px);">
        <div class="flex-1 w-full max-w-7xl mx-auto">
        <a href="{{ route('projects.manage') }}" class="text-xs font-medium text-gray-700 hover:text-blue-600 flex items-center mb-6">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali
        </a>
        
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Edit Pekerjaan</h2>

        <div class="mb-6">
            <h3 class="text-base font-bold text-gray-800">Detail Pekerjaan</h3>
            <p class="text-gray-500 text-xs">Berikan informasi lengkap agar calon pekerja memahami tugas yang diberikan.</p>
        </div>

        <form action="{{ route('projects.update', $project->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
                <!-- Judul Pekerjaan -->
                <div class="md:col-span-1">
                    <label for="title" class="block text-sm font-semibold text-gray-800 mb-2">Judul Pekerjaan</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $project->title) }}" 
                        class="w-full bg-white border border-gray-300 rounded shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-800"
                        required>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori Pekerjaan -->
                <div class="md:col-span-1">
                    <label for="category" class="block text-sm font-semibold text-gray-800 mb-2">Kategori Pekerjaan</label>
                    <input type="text" id="category" name="category" value="{{ old('category', $project->category) }}" 
                        class="w-full bg-white border border-gray-300 rounded shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-800">
                    @error('category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Anggaran (Rp) -->
                <div class="md:col-span-1">
                    <label for="budget" class="block text-sm font-semibold text-gray-800 mb-2">Anggaran (Rp)</label>
                    <div class="relative rounded shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-800 text-sm">Rp.</span>
                        </div>
                        <input type="number" id="budget" name="budget" value="{{ old('budget', $project->budget) }}" min="0" step="1000"
                            class="w-full bg-white border border-gray-300 rounded shadow-sm py-2 pl-10 pr-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-800">
                    </div>
                    @error('budget')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-6">
                <!-- Deskripsi Lengkap -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-gray-800 mb-2">Deskripsi Lengkap</label>
                    <textarea id="description" name="description" rows="7" 
                        class="w-full bg-white border border-gray-300 rounded shadow-sm py-3 px-4 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-800 leading-relaxed"
                        required>{{ old('description', $project->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-1 flex flex-col">
                    <!-- Batas Waktu (Deadline) -->
                    <div class="mb-auto">
                        <label for="deadline" class="block text-sm font-semibold text-gray-800 mb-2">Batas Waktu (Deadline)</label>
                        <input type="date" id="deadline" name="deadline" value="{{ old('deadline', $project->deadline ? \Carbon\Carbon::parse($project->deadline)->format('Y-m-d') : '') }}" 
                            class="w-full bg-white border border-gray-300 rounded shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-800">
                        @error('deadline')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Button -->
                    <div class="mt-8">
                        <button type="submit" class="w-full sm:w-auto bg-[#5bc0de] text-white py-2 px-10 rounded shadow-sm hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 font-semibold text-base transition-colors">
                            Selesai
                        </button>
                    </div>
                </div>
            </div>
        </form>
        </div>
        </div>
    </div>
</x-app-layout>
