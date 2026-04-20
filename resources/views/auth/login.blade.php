<x-guest-layout>
    <div class="p-4 sm:p-6">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Welcome back</h2>
            <p class="text-sm text-gray-500 mt-1">Please enter your details to sign in to your account.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-200 p-3 rounded-md">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="block font-medium text-sm text-gray-700">Email Address</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <input id="email" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" type="email" name="email" value="{{ old('email') }}" required autofocus />
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <div class="flex justify-between">
                    <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                    <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:text-gray-900">Forgot Password?</a>
                </div>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input id="password" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" type="password" name="password" required autocomplete="current-password" />
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <style>
                /* Hide default radio circle */
                .custom-radio input[type="radio"] {
                    display: none;
                }
                
                /* Custom styled container */
                .custom-radio label {
                    display: flex;
                    align-items: center;
                    cursor: pointer;
                    padding: 0.5rem 1rem;
                    border: 1px solid #d1d5db;
                    border-radius: 0.375rem;
                    transition: all 0.2s;
                }

                .custom-radio input[type="radio"]:checked + label {
                    border-color: #3b82f6;
                    background-color: #eff6ff;
                    color: #1d4ed8;
                }
            </style>

            <div class="mb-4">
                <p class="block font-medium text-sm text-gray-700 mb-2">Login As</p>
                <div class="flex gap-4">
                    <div class="custom-radio flex-1">
                        <input type="radio" id="role-client" name="role" value="client" checked>
                        <label for="role-client" class="w-full justify-center text-sm font-medium text-gray-700">Client</label>
                    </div>
                    <div class="custom-radio flex-1">
                        <input type="radio" id="role-worker" name="role" value="worker">
                        <label for="role-worker" class="w-full justify-center text-sm font-medium text-gray-700">Worker</label>
                    </div>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="block mb-6">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                    <span class="ml-2 text-sm text-gray-600">Keep me logged in</span>
                </label>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Sign In
                </button>
            </div>
            
            <div class="mt-6 text-center text-sm text-gray-500">
                Don't have an account yet? <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-600">Sign up for free</a>
            </div>
        </form>
    </div>
</x-guest-layout>
