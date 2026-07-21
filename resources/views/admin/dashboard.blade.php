<x-layout>
    <x-slot:title>
        Dashboard
    </x-slot:title>

    <section class="bg-white rounded-3xl shadow-lg p-12 border border-purple-100">
        <h1 class="text-4xl font-extrabold text-purple-800 mb-4">
            Welcome, Admin
        </h1>

        <p class="text-gray-600 text-lg max-w-2xl">
            Manage airports, aircrafts, routes, flights, and system settings.
        </p>

        <div class="mt-10 grid md:grid-cols-4 gap-8">

            <a href="" class="bg-purple-50 hover:bg-purple-100 transition p-6 rounded-xl shadow-sm text-center">
                <i class="fa-solid fa-building text-purple-800 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-800">Airports</h3>
            </a>

            <a href="" class="bg-purple-50 hover:bg-purple-100 transition p-6 rounded-xl shadow-sm text-center">
                <i class="fa-solid fa-plane text-purple-800 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-800">Aircrafts</h3>
            </a>

            <a href="" class="bg-purple-50 hover:bg-purple-100 transition p-6 rounded-xl shadow-sm text-center">
                <i class="fa-solid fa-route text-purple-800 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-800">Routes</h3>
            </a>

            <a href="" class="bg-purple-50 hover:bg-purple-100 transition p-6 rounded-xl shadow-sm text-center">
                <i class="fa-solid fa-plane-departure text-purple-800 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-800">Flights</h3>
            </a>

            <a href="" class="bg-purple-50 hover:bg-purple-100 transition p-6 rounded-xl shadow-sm text-center">
                <i class="fa-solid fa-plane-departure text-purple-800 text-3xl mb-3"></i>
                <h3 class="font-semibold text-gray-800">Reservations</h3>
            </a>
        </div>
    </section>
</x-layout>