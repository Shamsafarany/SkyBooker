{{-- resources/views/auth/register.blade.php --}}

<x-layout title="Register" header="Create Account">
    <div class="min-h-[80vh] flex items-center justify-center py-8">
        <div class="w-full max-w-2xl">
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-8">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-cyan-600 rounded-2xl shadow-lg mb-3">
                        <i class="fa-solid fa-plane text-white text-2xl"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Create Account</h2>
                    <p class="text-sm text-gray-500">Join SkyBooker today</p>
                </div>

                {{-- Error Messages --}}
                @if(session('error'))
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm flex items-center gap-2">
                        <i class="fa-regular fa-circle-exclamation"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm flex items-center gap-2">
                        <i class="fa fa-circle-exclamation"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Register Form --}}
                <form action="{{ route('register') }}" method="POST" class="space-y-4">
                    @csrf

                    {{-- Name Fields - Side by Side --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- First Name --}}
                        <x-form.input 
                            name="first_name"
                            label="First Name"
                            icon="fa-regular fa-user"
                            placeholder="John"
                            value="{{ old('first_name') }}"
                            required
                        />

                        {{-- Last Name --}}
                        <x-form.input 
                            name="last_name"
                            label="Last Name"
                            icon="fa-regular fa-user"
                            placeholder="Doe"
                            value="{{ old('last_name') }}"
                            required
                        />
                    </div>

                    {{-- Email --}}
                    <x-form.input 
                        name="email"
                        label="Email Address"
                        type="email"
                        icon="fa-regular fa-envelope"
                        placeholder="john@example.com"
                        value="{{ old('email') }}"
                        required
                    />

                    {{-- Phone --}}
                    <x-form.input 
                        name="phone"
                        label="Phone Number"
                        icon="fa-regular fa-phone"
                        placeholder="+1-555-123-4567"
                        value="{{ old('phone') }}"
                    />

                    {{-- Password Fields - Side by Side --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Password --}}
                        <x-form.input 
                            name="password"
                            label="Password"
                            type="password"
                            icon="fa-regular fa-lock"
                            placeholder="••••••••"
                            required
                        />

                        {{-- Password Confirmation --}}
                        <x-form.input 
                            name="password_confirmation"
                            label="Confirm Password"
                            type="password"
                            icon="fa-regular fa-lock"
                            placeholder="••••••••"
                            required
                        />
                    </div>

                    {{-- Terms --}}
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="terms" id="terms" required class="w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500">
                        <label for="terms" class="text-sm text-gray-600">
                            I agree to the 
                            <a href="#" class="text-cyan-600 hover:text-cyan-800">Terms of Service</a> 
                            and 
                            <a href="#" class="text-cyan-600 hover:text-cyan-800">Privacy Policy</a>
                        </label>
                    </div>
                    @error('terms')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    {{-- Submit Button --}}
                    <button type="submit" 
                            class="w-full py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fa fa-user-plus"></i>
                        Create Account
                    </button>
                </form>

                {{-- Login Link --}}
                <p class="text-center text-sm text-gray-500 mt-6">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-cyan-600 hover:text-cyan-800 font-medium">
                        Sign in
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-layout>