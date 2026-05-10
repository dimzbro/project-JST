<x-app-layout>
    <div class="mb-6">
        <a href="{{ route('worker.tasks.show', $task->id) }}" class="text-sm text-gray-500 hover:text-gray-700 mb-4 inline-block">&larr; Kembali</a>
        <h2 class="text-2xl font-bold text-gray-800 mt-2">Kirim Hasil Pekerjaan</h2>
    </div>

    @if(session('success'))
        <!-- Modal Sukses -->
        <div id="successModal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 50; display: flex; align-items: center; justify-content: center; background-color: rgba(17, 24, 39, 0.5);">
            <div style="background-color: white; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); padding: 2rem; width: 400px; position: relative; text-align: center;">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin-bottom: 1.5rem; text-align: center;">Pekerjaan Terkirim</h3>
                <div style="margin-left: auto; margin-right: auto; display: flex; align-items: center; justify-content: center; width: 6rem; height: 6rem; background-color: #22c55e; border-radius: 9999px; margin-bottom: 2rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                    <svg style="width: 3rem; height: 3rem; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <button type="button" onclick="closeModal()" style="color: #6b7280; font-size: 0.75rem; font-weight: 500; position: absolute; bottom: 1.5rem; left: 2rem; background: none; border: none; cursor: pointer;">Kembali</button>
            </div>
        </div>
        <script>
            function closeModal() {
                document.getElementById('successModal').style.display = 'none';
                window.location.href = "{{ route('worker.tasks.index') }}";
            }
        </script>
    @endif

    @if($errors->any())
        <div style="margin-bottom: 1rem; padding: 0.75rem 1rem; background-color: #fee2e2; color: #b91c1c; border-radius: 0.5rem; font-size: 0.875rem;">
            <ul style="list-style-type: disc; margin-left: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <form action="{{ route('worker.tasks.upload.store', $task->id) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf

            <!-- File Upload Section -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-800">File Pekerjaan</h3>
                <p class="text-gray-500 text-xs mb-3">Pastikan Semua dokumen sudah sesuai dengan persyaratan sebelum di kirim ke klien.</p>
                
                <div id="dropzone" class="border-2 border-dashed rounded-lg p-8 text-center cursor-pointer hover:bg-gray-50 transition" style="border-color: #d1d5db;" onclick="document.getElementById('file_tugas').click()">
                    <div id="upload-prompt">
                        <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        <p class="text-sm text-gray-500">Klik untuk upload file ke sini</p>
                    </div>
                    
                    <div id="file-preview-container" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4 text-left">
                        <!-- File cards will be injected here -->
                    </div>
                </div>
                <input type="file" name="file_tugas[]" id="file_tugas" class="hidden" multiple onchange="handleFiles(this.files)">
            </div>

            <!-- Notes Section -->
            <div class="mb-6">
                <h3 class="font-semibold text-gray-800 mb-2">Catatan untuk Klien (Opsional)</h3>
                <textarea name="notes" rows="6" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm" placeholder="Masukkan catatan..."></textarea>
            </div>

            <div class="flex items-center justify-between border-t border-gray-100 pt-4 mt-6">
                <div class="flex items-center text-xs text-gray-500 max-w-lg">
                    <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Dengan menekan tombol kirim, Anda menyatakan bahwa pekerjaan ini telah selesai sepenuhnya. klien memiliki waktu 3 hari untuk meninjau sebelum pembayaran otomatis di rilis.</span>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('worker.tasks.show', $task->id) }}" class="font-semibold text-gray-700 hover:text-gray-900 text-sm">Batal</a>
                    <button type="submit" class="bg-[#5bc0de] hover:bg-[#4eb0ce] text-white font-medium py-2 px-6 rounded-lg transition shadow-sm text-sm">
                        Kirim Pekerjaan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('file_tugas');
        const uploadPrompt = document.getElementById('upload-prompt');
        const previewContainer = document.getElementById('file-preview-container');

        let allSelectedFiles = [];

        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            if (allSelectedFiles.length === 0) {
                e.preventDefault();
                alert('Kolom file pekerjaan ini harus diisi. Silakan unggah minimal satu file hasil pekerjaan.');
            }
        });

        // Drag and drop handlers
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropzone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            dropzone.classList.add('bg-gray-100', 'border-blue-400');
        }

        function unhighlight(e) {
            dropzone.classList.remove('bg-gray-100', 'border-blue-400');
        }

        dropzone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            let dt = e.dataTransfer;
            let files = dt.files;
            handleFiles(files);
        }

        function formatBytes(bytes, decimals = 1) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function getFileIcon(filename) {
            const ext = filename.split('.').pop().toLowerCase();
            if (['ai', 'eps'].includes(ext)) {
                return '<div class="w-8 h-8 rounded bg-cyan-100 text-cyan-400 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg></div>';
            } else if (['jpg', 'jpeg', 'png', 'gif', 'pdf'].includes(ext)) {
                return '<div class="w-8 h-8 rounded bg-blue-100 text-blue-400 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>';
            }
            return '<div class="w-8 h-8 rounded bg-gray-100 text-gray-500 flex items-center justify-center"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg></div>';
        }

        function handleFiles(files) {
            if (files.length === 0) return;

            // Add newly selected files to our global array
            Array.from(files).forEach(file => {
                allSelectedFiles.push(file);
            });

            // Update the actual input element's files so form submission works
            const dataTransfer = new DataTransfer();
            allSelectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            fileInput.files = dataTransfer.files;

            renderPreviews();
        }

        function removeFile(index) {
            allSelectedFiles.splice(index, 1);
            
            // Update input
            const dataTransfer = new DataTransfer();
            allSelectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            fileInput.files = dataTransfer.files;

            renderPreviews();
        }

        function renderPreviews() {
            if (allSelectedFiles.length === 0) {
                uploadPrompt.classList.remove('hidden');
                previewContainer.classList.add('hidden');
                return;
            }

            uploadPrompt.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            previewContainer.innerHTML = '';

            allSelectedFiles.forEach((file, index) => {
                const iconSvg = getFileIcon(file.name);
                const card = document.createElement('div');
                card.className = 'flex items-center p-3 border border-gray-200 rounded-lg bg-white shadow-sm relative';
                card.innerHTML = `
                    <div class="flex items-center gap-3 w-full">
                        <div class="p-2 bg-gray-50 rounded">
                            ${iconSvg}
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="text-sm font-medium text-gray-700 truncate" title="${file.name}">${file.name}</p>
                            <p class="text-xs text-gray-400">${(file.size / (1024 * 1024)).toFixed(1)} MB</p>
                        </div>
                    </div>
                    <button type="button" onclick="removeFile(${index})" style="position: absolute; top: 0.5rem; right: 0.5rem; color: #9ca3af; cursor: pointer; border: none; background: transparent;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#9ca3af'" title="Hapus file">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                `;
                previewContainer.appendChild(card);
            });
        }
    </script>
</x-app-layout>
