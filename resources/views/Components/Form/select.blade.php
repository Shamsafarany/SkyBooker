@props(['name', 'label', 'options' => [], 'selected' => '', 'icon' => null])

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700 mb-1.5">{{ $label }}</label>
    <div class="relative">
        @if($icon)<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><i class="fa-solid {{ $icon }} text-cyan-500"></i></div>@endif
        <select name="{{ $name }}" {{ $attributes->merge(['class' => 'w-full ' . ($icon ? 'pl-10' : 'pl-4') . ' pr-10 py-3 rounded-xl border-2 border-gray-200 focus:border-cyan-500 focus:ring-4 focus:ring-cyan-200 focus:outline-none']) }}>
            <option value="">Select {{ $label }}</option>
            @foreach($options as $value => $label)
                <option value="{{ $value }}" {{ old($name, $selected) == $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    @error($name)<p class="mt-1.5 text-sm text-rose-600">{{ $message }}</p>@enderror
</div>