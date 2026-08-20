<nav class="bg-white shadow-lg fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fa-solid fa-plane-departure text-cyan-800 text-2xl"></i>
                <span class="text-2xl font-extrabold tracking-tight text-cyan-800">
                    SkyBooker
                </span>
            </div>
            <div class="flex gap-8 text-sm font-medium flex-wrap">
                @php
                    $adminLinks = [
                    ['label' => 'Login', 'route' => 'login'],
                    ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
                    ['label' => 'Airports', 'route' => 'admin.airports.index'],
                    ['label' => 'Airplanes', 'route' => 'admin.airplanes.index'],
                    ['label' => 'Flights', 'route' => 'admin.flights.index'],
                    ['label' => 'Bookings', 'route' => 'admin.bookings.index'],
                ];
                @endphp
                
            @foreach($adminLinks as $link)
                <x-nav-link 
                    href="{{ route($link['route']) }}" 
                    :active="request()->routeIs($link['route'])" 
                >
                    {{ $link['label'] }}
                </x-nav-link>
            @endforeach
            <x-nav-link >
            <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="font-bold text-cyan-700">
                        LOGOUT
                    </button>
                </form>
            </x-nav-link>
            </div>
        </div>
</nav>