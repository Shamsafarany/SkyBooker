@props(['active' => false])

<a {{ $attributes->merge(['class' => $active 
    ? 'text-purple-500 border-b-2 border-purple-500 pb-1 font-semibold text-[1.1em] uppercase tracking-[0.1em] transition duration-300 ease-in-out hover:scale-105 inline-block' 
    : 'text-gray-700 hover:text-purple-500 hover:text-[1.1em] hover:scale-105 transition duration-300 ease-in-out text-sm uppercase text-[1em] tracking-[0.1em] inline-block'
]) }}>{{ $slot }}</a>