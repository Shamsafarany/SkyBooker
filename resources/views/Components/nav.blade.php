<nav class="bg-white shadow-lg fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        {{-- Logo --}}
        <div class="flex items-center space-x-3">
            <i class="fa-solid fa-plane-departure text-cyan-800 text-2xl"></i>
            <span class="text-2xl font-extrabold tracking-tight text-cyan-800">
                SkyBooker
            </span>
        </div>

        {{-- Navigation Links --}}
        <div class="flex gap-6 text-sm font-medium flex-wrap items-center">
            @guest
                <x-nav-link href="{{ route('login') }}" :active="request()->routeIs('login')">
                    Login
                </x-nav-link>
                <x-nav-link href="{{ route('register') }}" :active="request()->routeIs('register')">
                    Register
                </x-nav-link>
            @endguest

            @auth
                <span class="text-gray-600 border-r-2 border-gray-200 pr-4 transition duration-300 ease-in-out hover:scale-105 inline-block">
                    Hi, 
                    <a href="{{ route('admin.profiles.index') }}">
                        <span class="font-semibold text-cyan-700 hover:text-cyan-500">{{ Auth::user()->first_name }}</span>!
                    </a>
                        
                </span>

                {{-- Admin links --}}
                <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                    Dashboard
                </x-nav-link>
                <x-nav-link href="{{ route('admin.airports.index') }}" :active="request()->routeIs('admin.airports.*')">
                    Airports
                </x-nav-link>
                <x-nav-link href="{{ route('admin.airplanes.index') }}" :active="request()->routeIs('admin.airplanes.*')">
                    Airplanes
                </x-nav-link>
                <x-nav-link href="{{ route('admin.flights.index') }}" :active="request()->routeIs('admin.flights.*')">
                    Flights
                </x-nav-link>
                <x-nav-link href="{{ route('admin.bookings.index') }}" :active="request()->routeIs('admin.bookings.*')">
                    Bookings
                </x-nav-link>

                {{-- Logout --}}
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-cyan-700 hover:text-cyan-500 font-bold transition">
                        LOGOUT
                    </button>
                </form>
            @endauth
        </div>
    </div>
</nav>