<x-layout title="Reset Password" header="Reset Your Password">
    <div class="min-h-[80vh] flex items-center justify-center">
        <div class="w-full max-w-md">

            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-8">
                
                {{-- Header --}}
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-cyan-600 rounded-2xl shadow-lg mb-3">
                        <i class="fa-solid fa-lock text-white text-2xl"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Reset Your Password</h2>
                    <p class="text-sm text-gray-500">Enter your new password below</p>
                </div>

                {{-- Error --}}
                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm flex items-center gap-2">
                        <i class="fa fa-circle-exclamation"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Success --}}
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl text-green-600 text-sm flex items-center gap-2">
                        <i class="fa fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Reset Password Form --}}
                <form action="{{ route('resetPassword') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Hidden Token --}}
                    <input type="hidden" name="token" value="{{ $token }}">

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-envelope text-gray-400"></i>
                            </div>
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition outline-none"
                                placeholder="you@example.com"
                                required
                            >
                        </div>
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            New Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition outline-none"
                                placeholder="••••••••"
                                required
                            >
                        </div>
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Confirm Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition outline-none"
                                placeholder="••••••••"
                                required
                            >
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" 
                            class="w-full py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i>
                        Update Password
                    </button>
                </form>

                {{-- Back to Login --}}
                <p class="text-center text-sm text-gray-500 mt-6">
                    Return to login?
                    <a href="{{ route('login') }}" class="text-cyan-600 hover:text-cyan-800 font-medium">
                        Go back
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-layout>
