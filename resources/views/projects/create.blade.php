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

        <form action="{{ route('projects.store') }}" method="POST" id="job-form" novalidate>
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Judul Pekerjaan -->
                <div class="md:col-span-1">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Pekerjaan</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" 
                        class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <p class="text-red-500 text-xs mt-1 hidden" id="title-error"></p>
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori Pekerjaan -->
                <div class="md:col-span-1">
                    <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Kategori Pekerjaan</label>
                    <input type="text" id="category" name="category" value="{{ old('category') }}" 
                        class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    <p class="text-red-500 text-xs mt-1 hidden" id="category-error"></p>
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
                        <input type="text" id="budget" name="budget" value="{{ old('budget') }}"
                            class="w-full border border-gray-300 rounded-md shadow-sm py-2 pl-10 pr-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <p class="text-red-500 text-xs mt-1 hidden" id="budget-error"></p>
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
                        class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('description') }}</textarea>
                    <p class="text-red-500 text-xs mt-1 hidden" id="description-error"></p>
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
                        <p class="text-red-500 text-xs mt-1 hidden" id="deadline-error"></p>
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('job-form');
                if (!form) return;

                form.addEventListener('submit', function(e) {
                    let isValid = true;

                    // Get inputs
                    const titleInput = document.getElementById('title');
                    const categoryInput = document.getElementById('category');
                    const budgetInput = document.getElementById('budget');
                    const descriptionInput = document.getElementById('description');
                    const deadlineInput = document.getElementById('deadline');

                    // Get values
                    const titleVal = titleInput.value.trim();
                    const categoryVal = categoryInput.value.trim();
                    const budgetVal = budgetInput.value.trim();
                    const descriptionVal = descriptionInput.value.trim();
                    const deadlineVal = deadlineInput.value.trim();

                    // Get error elements
                    const titleError = document.getElementById('title-error');
                    const categoryError = document.getElementById('category-error');
                    const budgetError = document.getElementById('budget-error');
                    const descriptionError = document.getElementById('description-error');
                    const deadlineError = document.getElementById('deadline-error');

                    // Helper to show error
                    function showError(input, errorEl, message) {
                        errorEl.textContent = message;
                        errorEl.classList.remove('hidden');
                        input.classList.add('border-red-500');
                        input.classList.remove('border-gray-300');
                    }

                    // Helper to clear error
                    function clearError(input, errorEl) {
                        errorEl.textContent = '';
                        errorEl.classList.add('hidden');
                        input.classList.remove('border-red-500');
                        input.classList.add('border-gray-300');
                    }

                    // Clear previous errors
                    clearError(titleInput, titleError);
                    clearError(categoryInput, categoryError);
                    clearError(budgetInput, budgetError);
                    clearError(descriptionInput, descriptionError);
                    clearError(deadlineInput, deadlineError);

                    // 1. Title Validation
                    if (titleVal === '') {
                        showError(titleInput, titleError, 'Judul pekerjaan wajib diisi');
                        isValid = false;
                    }

                    // 2. Category Validation
                    if (categoryVal === '') {
                        showError(categoryInput, categoryError, 'Kategori pekerjaan wajib dipilih');
                        isValid = false;
                    }

                    // 3, 4, 5. Budget Validation
                    if (budgetVal === '') {
                        showError(budgetInput, budgetError, 'Anggaran wajib diisi');
                        isValid = false;
                    } else if (!/^\d+$/.test(budgetVal)) {
                        showError(budgetInput, budgetError, 'Anggaran harus berupa angka');
                        isValid = false;
                    } else if (parseInt(budgetVal, 10) <= 0) {
                        showError(budgetInput, budgetError, 'Anggaran harus lebih dari 0');
                        isValid = false;
                    }

                    // 6. Description Validation
                    if (descriptionVal === '') {
                        showError(descriptionInput, descriptionError, 'Deskripsi tugas wajib diisi');
                        isValid = false;
                    }

                    // 7, 8. Deadline Validation
                    if (deadlineVal === '') {
                        showError(deadlineInput, deadlineError, 'deadline wajib dipilih');
                        isValid = false;
                    } else {
                        const selectedDate = new Date(deadlineVal);
                        selectedDate.setHours(0, 0, 0, 0);

                        const today = new Date();
                        today.setHours(0, 0, 0, 0);

                        if (selectedDate < today) {
                            showError(deadlineInput, deadlineError, 'Deadline tidak boleh di masa lalu');
                            isValid = false;
                        }
                    }

                    if (!isValid) {
                        e.preventDefault();
                    }
                });
            });
        </script>
    </div>
</x-app-layout>
