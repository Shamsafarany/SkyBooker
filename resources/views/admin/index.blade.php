<x-layout>
    <x-slot:title>
        Home
    </x-slot:title>
    
    <section class="relative bg-gradient-to-br from-purple-600 to-purple-800 text-white rounded-3xl shadow-xl overflow-hidden">
        <div class="px-10 py-24 max-w-7xl mx-auto">
            <h1 class="text-5xl font-extrabold mb-6 leading-tight text-purple-800">
                Discover a New Way to Fly
            </h1>

            <p class="text-lg text-gray-400 max-w-xl mb-10">
                SkyBooker brings you a seamless flight booking experience with premium comfort,
                modern design, and world‑class destinations.
            </p>

            <a href="#" class="inline-block bg-white text-purple-700 font-semibold px-8 py-3 rounded-full shadow-md hover:bg-purple-100 transition">
                Book a Flight
            </a>
        </div>
        <i class="fa-solid fa-plane-departure absolute right-10 top-10 text-white/30 text-7xl rotate-12"></i>
    </section>

    <section class="mt-20">
        <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">
            Why Choose SkyBooker?
        </h2>

        <div class="grid md:grid-cols-3 gap-10">

            <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition">
                <i class="fa-solid fa-ticket text-purple-600 text-4xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Easy Booking</h3>
                <p class="text-gray-600">
                    A fast, intuitive booking system designed for comfort and simplicity.
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition">
                <i class="fa-solid fa-plane text-purple-600 text-4xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Premium Flights</h3>
                <p class="text-gray-600">
                    Fly with top airlines offering luxury cabins and world‑class service.
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition">
                <i class="fa-solid fa-earth-europe text-purple-600 text-4xl mb-4"></i>
                <h3 class="text-xl font-semibold mb-2">Global Destinations</h3>
                <p class="text-gray-600">
                    Explore beautiful cities and exotic locations across the world.
                </p>
            </div>

        </div>
    </section>


    <!-- CTA Section -->
    <section class="mt-24 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">
            Ready for Your Next Journey?
        </h2>

        <a href="#" class="inline-block bg-purple-700 text-white font-semibold px-10 py-4 rounded-full shadow-lg hover:bg-purple-800 transition">
            Search Flights
        </a>
    </section>
</x-layout>


