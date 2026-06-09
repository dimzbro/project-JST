<x-app-layout>
    <!-- Header Section -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Manajemen Pengguna</h1>
            <p class="text-gray-500 text-sm mt-1">Pantau dan kelola seluruh akun pengguna yang terdaftar di platform.</p>
        </div>
        
        <!-- Search bar -->
        <div class="w-full md:w-80">
            <form method="GET" action="{{ route('admin.users.index') }}" class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
            </form>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="mb-6 p-4 text-green-700 bg-green-50 rounded-lg border border-green-200 flex items-center text-sm shadow-sm">
            <svg class="w-5 h-5 mr-2 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 text-red-700 bg-red-50 rounded-lg border border-red-200 flex items-center text-sm shadow-sm">
            <svg class="w-5 h-5 mr-2 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Users Table Card -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="py-4 px-6 text-[13px] font-semibold text-gray-700 uppercase tracking-wider">Pengguna</th>
                        <th class="py-4 px-6 text-[13px] font-semibold text-gray-700 uppercase tracking-wider">Peran</th>
                        <th class="py-4 px-6 text-[13px] font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="py-4 px-6 text-[13px] font-semibold text-gray-700 uppercase tracking-wider">Bergabung Pada</th>
                        <th class="py-4 px-6 text-[13px] font-semibold text-gray-700 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <!-- Pengguna Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-gray-200 border-2 border-gray-100 overflow-hidden flex-shrink-0 relative group">
                                        @if($user->profile_photo_path)
                                            <img src="{{ Storage::url($user->profile_photo_path) }}" alt="Profile Photo" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-full h-full text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Peran -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                                {{ $user->role_display_name }}
                            </td>
                            
                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->is_active)
                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Bergabung Pada -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                {{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}
                            </td>
                            
                            <!-- Aksi -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium relative">
                                @if($user->id !== Auth::id())
                                    <div class="inline-block text-left" id="dropdown-parent-{{ $user->id }}">
                                        <button type="button" onclick="toggleDropdown({{ $user->id }}, event)" class="text-gray-400 hover:text-gray-600 focus:outline-none p-1.5 rounded-full hover:bg-gray-100 transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                            </svg>
                                        </button>
                                        
                                        <!-- Elegant Dropdown Menu -->
                                        <div id="dropdown-{{ $user->id }}" class="hidden origin-top-right absolute right-6 mt-1 w-48 rounded-lg shadow-lg bg-white border border-gray-100 ring-1 ring-black ring-opacity-5 focus:outline-none z-30 transition transform duration-100 scale-95 opacity-0">
                                            <div class="py-1">
                                                @if($user->is_active)
                                                    <button type="button" onclick="openConfirmationModal({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}', 'deactivate')" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 transition flex items-center font-medium">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                        </svg>
                                                        Nonaktifkan Akun
                                                    </button>
                                                @else
                                                    <button type="button" onclick="openConfirmationModal({{ $user->id }}, '{{ $user->first_name }} {{ $user->last_name }}', 'activate')" class="w-full text-left px-4 py-2 text-sm text-emerald-600 hover:bg-emerald-50 hover:text-emerald-700 transition flex items-center font-medium">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Aktifkan Akun
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 font-normal italic">Anda (Admin)</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500 italic bg-white">
                                Belum ada data pengguna yang terdaftar atau cocok dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Activation/Deactivation Confirmation Modal -->
    <div id="actionModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div id="modalContent" class="bg-white rounded-2xl p-8 max-w-md w-[90%] text-center shadow-2xl border border-gray-100 transform scale-90 translate-y-4 transition-all duration-300">
            <!-- Modal Visual Icon -->
            <div id="modalIconContainer" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-5 transition-colors duration-300">
                <svg id="modalIcon" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
            </div>

            <!-- Modal Titles -->
            <h3 id="modalTitle" class="text-xl font-bold text-gray-900 mb-2 leading-tight">Konfirmasi Aksi</h3>
            <p id="modalDescription" class="text-sm text-gray-500 leading-relaxed mb-6"></p>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-center">
                <button type="button" onclick="closeConfirmationModal()" class="flex-1 py-2.5 px-4 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 font-semibold rounded-xl text-sm transition focus:outline-none">
                    Batal
                </button>
                <form id="modalForm" method="POST" action="" class="flex-1 m-0">
                    @csrf
                    <button type="submit" id="modalSubmitBtn" class="w-full py-2.5 px-4 text-white font-semibold rounded-xl text-sm transition focus:outline-none shadow-md"></button>
                </form>
            </div>
        </div>
    </div>

    <!-- Dropdown Control Javascript -->
    <script>
        let openDropdownId = null;

        function toggleDropdown(userId, event) {
            event.stopPropagation();
            
            const dropdown = document.getElementById(`dropdown-${userId}`);
            
            if (openDropdownId && openDropdownId !== userId) {
                closeDropdown(openDropdownId);
            }
            
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                // Allow animation
                setTimeout(() => {
                    dropdown.classList.remove('scale-95', 'opacity-0');
                    dropdown.classList.add('scale-100', 'opacity-100');
                }, 10);
                openDropdownId = userId;
            } else {
                closeDropdown(userId);
            }
        }

        function closeDropdown(userId) {
            const dropdown = document.getElementById(`dropdown-${userId}`);
            if (dropdown) {
                dropdown.classList.remove('scale-100', 'opacity-100');
                dropdown.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 100);
            }
            if (openDropdownId === userId) {
                openDropdownId = null;
            }
        }

        // Close dropdowns when clicking outside
        window.addEventListener('click', function(e) {
            if (openDropdownId) {
                const parent = document.getElementById(`dropdown-parent-${openDropdownId}`);
                if (parent && !parent.contains(e.target)) {
                    closeDropdown(openDropdownId);
                }
            }
        });

        // Confirmation Modal Functions
        function openConfirmationModal(userId, userName, actionType) {
            if (openDropdownId) {
                closeDropdown(openDropdownId);
            }

            const modal = document.getElementById('actionModal');
            const content = document.getElementById('modalContent');
            const iconContainer = document.getElementById('modalIconContainer');
            const icon = document.getElementById('modalIcon');
            const title = document.getElementById('modalTitle');
            const desc = document.getElementById('modalDescription');
            const submitBtn = document.getElementById('modalSubmitBtn');
            const form = document.getElementById('modalForm');

            // Set Form action
            form.action = `/admin/pengguna/${userId}/toggle`;

            if (actionType === 'deactivate') {
                // Setup Deactivate
                iconContainer.className = 'w-16 h-16 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-5 border border-red-100';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>';
                title.textContent = 'Nonaktifkan Akun';
                desc.textContent = `Apakah Anda yakin ingin menonaktifkan akun ${userName}? Pengguna ini tidak akan bisa mengambil pekerjaan baru atau mengunggah hasil pekerjaan mereka.`;
                submitBtn.textContent = 'Ya, Nonaktifkan';
                submitBtn.className = 'w-full py-2.5 px-4 text-white font-semibold rounded-xl text-sm bg-red-600 hover:bg-red-700 transition focus:outline-none shadow-md hover:shadow-lg';
            } else {
                // Setup Activate
                iconContainer.className = 'w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mx-auto mb-5 border border-emerald-100';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                title.textContent = 'Aktifkan Akun';
                desc.textContent = `Apakah Anda yakin ingin mengaktifkan kembali akun ${userName}? Pengguna ini akan dapat menggunakan seluruh fitur platform seperti biasa.`;
                submitBtn.textContent = 'Ya, Aktifkan';
                submitBtn.className = 'w-full py-2.5 px-4 text-white font-semibold rounded-xl text-sm bg-emerald-600 hover:bg-emerald-700 transition focus:outline-none shadow-md hover:shadow-lg';
            }

            // Show modal with animation
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-90', 'translate-y-4');
                modal.classList.add('opacity-100');
                content.classList.add('scale-100', 'translate-y-0');
            }, 10);
        }

        function closeConfirmationModal() {
            const modal = document.getElementById('actionModal');
            const content = document.getElementById('modalContent');

            modal.classList.remove('opacity-100');
            content.classList.remove('scale-100', 'translate-y-0');
            modal.classList.add('opacity-0');
            content.classList.add('scale-90', 'translate-y-4');

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</x-app-layout>
