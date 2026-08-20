<x-layout title="Login" header="Sign In">
    <div class="min-h-[80vh] flex items-center justify-center">
        <div class="w-full max-w-md">
            {{-- Login Card --}}
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-8">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-cyan-600 rounded-2xl shadow-lg mb-3">
                        <i class="fa-solid fa-plane text-white text-2xl"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Welcome Back</h2>
                    <p class="text-sm text-gray-500">Sign in to your account to continue</p>
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

                {{-- Login Form --}}
                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-regular fa-envelope text-gray-400"></i>
                            </div>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                value="{{ old('email') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition outline-none"
                                placeholder="admin@skybooker.com"
                                required
                                autofocus
                            >
                        </div>
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa fa-lock text-gray-400"></i>
                            </div>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition outline-none"
                                placeholder="••••••••"
                                required
                            >
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i class="fa-regular fa-eye" id="passwordToggle"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500">
                            Remember me
                        </label>
                        <a href="#" class="text-sm text-cyan-600 hover:text-cyan-800 font-medium">
                            Forgot password?
                        </a>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" 
                            class="w-full py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-semibold rounded-xl transition shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <i class="fa fa-user-plus"></i>
                        Sign In
                    </button>
                </form>

                {{-- Register Link --}}
                <p class="text-center text-sm text-gray-500 mt-6">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-cyan-600 hover:text-cyan-800 font-medium">
                        Create one
                    </a>
                </p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
    @endpush
</x-layout>