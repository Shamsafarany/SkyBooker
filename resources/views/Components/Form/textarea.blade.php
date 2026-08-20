@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'icon' => null,
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'rows' => 4,
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

    {{-- Textarea Wrapper --}}
    <div class="relative">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-start pt-3 pointer-events-none">
                <i class="fa-solid {{ $icon }} text-gray-400 text-sm"></i>
            </div>
        @endif

        <textarea 
            name="{{ $name }}" 
            id="{{ $name }}"
            placeholder="{{ $placeholder }}"
            rows="{{ $rows }}"
            {{ $required ? 'required' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $attributes->merge(['class' => 'w-full ' . ($icon ? 'pl-10' : 'pl-4') . ' pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:ring-3 focus:ring-cyan-200 transition-all duration-200 focus:outline-none resize-none']) }}
        >{{ old($name, $value) }}</textarea>
    </div>

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