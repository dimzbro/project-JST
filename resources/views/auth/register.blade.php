<x-guest-layout>
    <div class="p-4 sm:p-6">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Create your account</h2>
            <p class="text-sm text-gray-500 mt-1">Get started today by filling in your information below.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-4">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-xs text-gray-500">First Name</label>
                    <input id="first_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus />
                    @error('first_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-xs text-gray-500">Last Name</label>
                    <input id="last_name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" type="text" name="last_name" value="{{ old('last_name') }}" required />
                    @error('last_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs text-gray-500">Email</label>
                    <input id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" type="email" name="email" value="{{ old('email') }}" required />
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Phone Number -->
                <div>
                    <label for="phone_number" class="block text-xs text-gray-500">Phone Number</label>
                    <input id="phone_number" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" type="text" name="phone_number" value="{{ old('phone_number') }}" />
                    @error('phone_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs text-gray-500">Password</label>
                    <input id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" type="password" name="password" required />
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs text-gray-500">Confirm Password</label>
                    <input id="password_confirmation" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" type="password" name="password_confirmation" required />
                </div>
            </div>

            <div class="flex items-start mb-6">
                <div class="flex items-center h-5">
                    <input id="terms" name="terms" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded" required>
                </div>
                <div class="ml-3 text-xs">
                    <label for="terms" class="font-medium text-gray-500">By creating an account, I agree to JobConnect's <a href="#" class="text-blue-500">Terms of Service</a> and <a href="#" class="text-blue-500">Privacy Policy</a>.</label>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create Account
                </button>
            </div>
            
        </form>
    </div>
</x-guest-layout>
