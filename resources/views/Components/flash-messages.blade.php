@props([
    'dismissible' => true,
    'autoDismiss' => true,
    'autoDismissTime' => 5000,
])

@php
    $messages = [
    'success' => [
        'icon' => 'fa-regular fa-circle-check',
        'color' => 'text-white',
        'bg' => 'bg-emerald-700',   
        'border' => 'border-emerald-800',
        'text' => 'text-white',
    ],
    'error' => [
        'icon' => 'fa fa-circle-exclamation',
        'color' => 'text-white',
        'bg' => 'bg-red-700',    
        'border' => 'border-red-800',
        'text' => 'text-white',
    ],
    'warning' => [
        'icon' => 'fa-regular fa-triangle-exclamation',
        'color' => 'text-white',
        'bg' => 'bg-amber-700',  
        'border' => 'border-amber-800',
        'text' => 'text-white',
    ],
    'info' => [
        'icon' => 'fa-regular fa-circle-info',
        'color' => 'text-white',
        'bg' => 'bg-blue-700',           
        'border' => 'border-blue-800',
        'text' => 'text-white',
    ],
];
@endphp

<div id="flash-messages" class="space-y-3">
    @foreach(['success', 'error', 'warning', 'info'] as $type)
        @if(session($type))
            @php
                $msg = $messages[$type];
                $message = session($type);
                $id = 'flash-' . $type . '-' . uniqid();
            @endphp
            
            <div id="{{ $id }}" 
                    class="p-4 border rounded-xl text-sm flex items-center justify-between {{ $msg['bg'] }} {{ $msg['border'] }} {{ $msg['text'] }}"
                    role="alert">
                <div class="flex items-center gap-3">
                    <i class="{{ $msg['icon'] }} {{ $msg['color'] }} text-lg"></i>
                    <span>{{ $message }}</span>
                </div>
                
                @if($dismissible)
                    <button onclick="dismissFlash('{{ $id }}')" 
                            class="text-white hover:text-gray-300 transition">
                        <i class="fa fa-xmark text-lg"></i>
                    </button>
                @endif
            </div>
            
            @if($autoDismiss)
                <script>
                    setTimeout(function() {
                        var element = document.getElementById('{{ $id }}');
                        if (element) {
                            element.style.transition = 'opacity 0.5s ease';
                            element.style.opacity = '0';
                            setTimeout(function() {
                                element.remove();
                            }, 500);
                        }
                    }, {{ $autoDismissTime }});
                </script>
            @endif
        @endif
    @endforeach
</div>

@if($dismissible)
    <script>
        function dismissFlash(id) {
            var element = document.getElementById(id);
            if (element) {
                element.style.transition = 'opacity 0.3s ease';
                element.style.opacity = '0';
                setTimeout(function() {
                    element.remove();
                }, 300);
            }
        }
    </script>
@endif