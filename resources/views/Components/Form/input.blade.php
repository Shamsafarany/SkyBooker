@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'icon' => null,
    'required' => false,
    'helper' => null,
    'readonly' => false,
])

<div>
    {{-- Label --}}
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700 mb-1.5">
            {{ $label }}
            @if($required)<span class="text-rose-500">*</span>@endif
        </label>
    @endif

    {{-- Input Wrapper --}}
    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid {{ $icon }} text-cyan-500 text-sm"></i>
            </div>
        @endif

        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $name }}"
            value="{{ $readonly ? $value : old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            min="{{ $type === 'number' ? 1 : '' }}"
            {{ $required ? 'required' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $attributes->merge(['class' => 'w-full ' . ($icon ? 'pl-10' : 'pl-4') . ' pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:ring-3 focus:ring-cyan-200 transition-all duration-200 focus:outline-none ' . ($readonly ? 'bg-gray-100 cursor-not-allowed' : '')]) }}
        >
    </div>

    {{-- Helper Text --}}
    @if($helper)
        <p class="mt-1 text-xs text-gray-400">{{ $helper }}</p>
    @endif

    {{-- Error Message --}}
    @error($name)
        <p class="mt-1.5 text-sm text-rose-600"><i class="fa-regular fa-circle-exclamation mr-1"></i>{{ $message }}</p>
    @enderror
</div>