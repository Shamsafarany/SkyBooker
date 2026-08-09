@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'icon' => null,
    'required' => false,
])

<div>
    {{-- Label with Asterisk --}}
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700 mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-rose-500">*</span>
            @endif
        </label>
    @endif

    {{-- Select Wrapper --}}
    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid {{ $icon }} text-cyan-500 text-sm"></i>
            </div>
        @endif

        <select 
            name="{{ $name }}" 
            id="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'w-full ' . ($icon ? 'pl-10' : 'pl-4') . ' pr-10 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:ring-3 focus:ring-cyan-200 transition-all duration-200 focus:outline-none appearance-none']) }}
        >
            @if(!$selected)
                <option value="">Select {{ $label }}</option>
            @endif
            
            @foreach($options as $value => $label)
                <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        {{-- Dropdown Arrow --}}
        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
            <i class="fa-solid fa-chevron-down text-gray-400 text-sm"></i>
        </div>
    </div>

    {{-- Error Message --}}
    @error($name)
        <div class="mt-1.5 space-y-1">
            @foreach($errors->get($name) as $message)
                <p class="text-sm text-rose-600 flex items-start gap-1.5">
                    <i class="fa-regular fa-circle-exclamation mt-0.5 text-xs"></i>
                    {{ $message }}
                </p>
            @endforeach
        </div>
    @enderror
</div>