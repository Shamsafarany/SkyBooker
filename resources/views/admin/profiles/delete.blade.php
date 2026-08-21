<x-layout title="Delete Account" header="Delete Account">
    <div class="max-w-2xl mx-auto mt-10">
        <div class="bg-white rounded-2xl shadow-xl border border-red-200 overflow-hidden">
            {{-- Header --}}
            <div class="px-6 py-5 bg-red-50 border-b border-red-200 flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 text-xl">
                    <i class="fa fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-red-800">Delete Account</h3>
                    <p class="text-sm text-red-600">This action cannot be undone</p>
                </div>
            </div>

            <div class="p-6">
                {{-- Warning Message --}}
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-sm text-red-700 font-medium">⚠️ Warning: You are about to permanently delete your account.</p>
                    <ul class="mt-2 text-sm text-red-600 space-y-1 list-disc list-inside">
                        <li>All your personal information will be removed</li>
                        <li>Your bookings and history will be deleted</li>
                        <li>You will lose access to all features</li>
                        <li>This action cannot be reversed</li>
                    </ul>
                </div>

                {{-- User Info --}}
                <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-200">
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Account to delete:</span>
                        <span class="font-semibold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</span>
                        <span class="text-gray-400">({{ $user->email }})</span>
                    </p>
                </div>

                {{-- Delete Form --}}
                <form action="{{ route('admin.profiles.destroy', $user) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('DELETE')

                    {{-- Password Confirmation --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Enter your password to confirm
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition outline-none"
                                placeholder="Enter your current password"
                                required
                            >
                        </div>
                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirmation Checkbox --}}
                    <div class="flex items-center gap-3 p-4 bg-red-50 rounded-xl border border-red-200">
                        <input 
                            type="checkbox" 
                            name="confirmation" 
                            id="confirmation" 
                            value="1"
                            required
                            class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500"
                        >
                        <label for="confirmation" class="text-sm text-red-700 font-medium">
                            I understand that this action is permanent and cannot be undone
                        </label>
                    </div>
                    @error('confirmation')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror

                    {{-- Buttons --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.profiles.index') }}" 
                            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition text-sm font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition text-sm font-medium flex items-center gap-2">
                            <i class="fa-regular fa-trash-can"></i>
                            Permanently Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Back to Profile --}}
        <div class="mt-4 text-center">
            <a href="{{ route('admin.profiles.index') }}" class="text-sm text-gray-500 hover:text-cyan-600 transition">
                <i class="fa fa-arrow-left mr-1"></i>
                Back to Profile
            </a>
        </div>
    </div>
</x-layout>