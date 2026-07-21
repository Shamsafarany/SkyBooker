<nav class="bg-white shadow-lg fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fa-solid fa-plane-departure text-purple-800 text-2xl"></i>
                <span class="text-2xl font-extrabold tracking-tight text-purple-800">
                    SkyBooker
                </span>
            </div>
            <div class="flex space-x-8 text-sm font-medium">
                @php
                    $adminLinks = [
                    ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'type' => 'a'],
                    ['label' => 'Airports', 'route' => 'admin.airports.index', 'type' => 'a'],
                    ['label' => 'Airplanes', 'route' => 'admin.airplanes.index', 'type' => 'a'],
                    ['label' => 'Flights', 'route' => 'admin.flights.index', 'type' => 'a'],
                    ['label' => 'Reservations', 'route' => 'admin.reservations.index', 'type' => 'a'],
                    //['label' => 'Logout', 'route' => 'logout'],
                ];
                @endphp
                
            @foreach($adminLinks as $link)
                <x-nav-link 
                    href="{{ route($link['route']) }}" 
                    :active="request()->routeIs($link['route'])" 
                    type="{{ $link['type'] }}"
                >
                    {{ $link['label'] }}
                </x-nav-link>
            @endforeach
            </div>
        </div>
</nav>