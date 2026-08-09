<x-layout title="Dashboard">
    <section class="bg-white rounded-3xl shadow-xl p-12 border border-gray-100">
        <h1 class="text-4xl font-extrabold text-cyan-800 mb-4">
            Welcome, Admin
        </h1>

        <p class="text-gray-600 text-lg max-w-2xl">
            Manage airports, aircrafts, flights, and system settings.
        </p>

        <div class="mt-10 grid md:grid-cols-4 gap-8">

            <a href="{{ route('admin.airports.index') }}" class="bg-cyan-50 hover:bg-cyan-100 transition p-6 rounded-xl shadow-md text-center">
                <i class="fa-solid fa-building text-cyan-800 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-800">Airports</h3>
            </a>

            <a href="{{ route('admin.airplanes.index') }}" class="bg-cyan-50 hover:bg-cyan-100 transition p-6 rounded-xl shadow-md text-center">
                <i class="fa-solid fa-plane text-cyan-800 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-800">Aircrafts</h3>
            </a>

            <a href="{{ route('admin.flights.index') }}" class="bg-cyan-50 hover:bg-cyan-100 transition p-6 rounded-xl shadow-md text-center">
                <i class="fa-solid fa-plane-departure text-cyan-800 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-800">Flights</h3>
            </a>

            <a href="{{ route('admin.bookings.index') }}" class="bg-cyan-50 hover:bg-cyan-100 transition p-6 rounded-xl shadow-md text-center">
                <i class="fa-solid fa-plane-departure text-cyan-800 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-800">Bookings</h3>
            </a>
        </div>
    </section>
</x-layout>