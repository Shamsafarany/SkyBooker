@props(['active' => false, 'type'=> 'a'])

@if($type=='a')
    <a {{ $attributes->merge(['class' => $active 
    ? 'text-purple-700 border-b-2 border-purple-700 pb-1 font-semibold' 
    : 'text-gray-700 hover:text-purple-800 transition'
    ]) }}>{{$slot}}</a>
@else
    <button class='text-gray-700 hover:text-purple-800 transition font-medium'>{{$slot}}</button>
@endif

