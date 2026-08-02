@props([
    'title' => 'Add New',
    'subtitle' => null,
    'action' => '#',
    'method' => 'POST',
    'submitLabel' => 'Create',
    'cancelRoute' => '#',
    'icon' => 'fa-plus',
])

<div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
    {{-- Header --}}
    <div class="px-8 py-6 bg-gradient from-cyan-50 to-cyan-100/50 border-b border-cyan-200">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-cyan-600 flex items-center justify-center shadow-lg shadow-cyan-600/30 hover:bg-cyan-400">
                <i class="fa-solid {{ $icon }} text-white text-xl"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $title }}</h2>
                @if($subtitle)
                    <p class="text-sm text-gray-500 m">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ $action }}" method="POST" class="p-8 space-y-6">
        @csrf
        @if(strtoupper($method) === 'PUT' || strtoupper($method) === 'PATCH')
            @method($method)
        @endif

        <div class="space-y-6">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        <div class="border-t border-gray-200 pt-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <span>All fields marked with <span class="text-rose-500">*</span> are required</span>
                </div>
                <div class="flex gap-3">
                    <a href="{{ $cancelRoute }}" 
                       class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-xl transition-all duration-200">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-2.5 text-sm font-semibold text-white bg-cyan-800 hover:bg-cyan-500 rounded-xl transition-all duration-200 shadow-lg shadow-gray-600/30 hover:shadow-xl hover:shadow-cyan-600/40 flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2">
                        <i class="fa-solid {{ $icon }}"></i>
                        {{ $submitLabel }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>