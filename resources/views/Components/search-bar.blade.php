{{-- resources/views/components/search-bar.blade.php --}}

@props([
    'route' => '#',
    'placeholder' => 'Search...',
    'buttonText' => 'Search',
    'queryParam' => 'q',
    'showReset' => false,
    'filters' => [],
])

<div class="w-full max-w-3xl mx-auto mt-2 mb-8">
    <form action="{{ $route }}" method="GET" class="flex flex-wrap items-center justify-center gap-3">
        {{-- Search Input with Clear Button Inside --}}
        <div class="relative flex-1 min-w-[200px]">
            <input 
                type="text" 
                name="{{ $queryParam }}" 
                value="{{ request($queryParam) }}"
                placeholder="{{ $placeholder }}"
                class="w-full px-5 py-3.5 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-cyan-600 focus:outline-none bg-white shadow-xl text-base"
                autocomplete="off"
            >
            
            {{-- Clear Button Inside Input --}}
            @if($showReset && request()->filled($queryParam))
                <a 
                    href="{{ url()->current() }}" 
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition"
                    title="Clear search"
                >
                    <i class="fa-solid fa-xmark text-xl"></i>
                </a>
            @endif
        </div>

        {{-- Search Button --}}
        <button 
            type="submit"
            class="bg-cyan-800 text-white px-6 py-3.5 rounded-xl hover:bg-cyan-600 transition shadow-md flex items-center gap-2 font-medium"
        >
            <i class="fa-solid fa-magnifying-glass"></i>
            {{ $buttonText }}
        </button>
    </form>
</div>