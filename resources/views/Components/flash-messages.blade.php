@props([
    'dismissible' => true,
    'autoDismiss' => true,
    'autoDismissTime' => 5000,
])

@php
    $messages = [
        'success' => [
            'icon' => 'fa-regular fa-circle-check',
            'color' => 'text-emerald-500',
            'bg' => 'bg-emerald-50',
            'border' => 'border-emerald-200',
            'text' => 'text-emerald-700',
        ],
        'error' => [
            'icon' => 'fa-regular fa-circle-exclamation',
            'color' => 'text-red-500',
            'bg' => 'bg-red-50',
            'border' => 'border-red-200',
            'text' => 'text-red-700',
        ],
        'warning' => [
            'icon' => 'fa-regular fa-triangle-exclamation',
            'color' => 'text-amber-500',
            'bg' => 'bg-amber-50',
            'border' => 'border-amber-200',
            'text' => 'text-amber-700',
        ],
        'info' => [
            'icon' => 'fa-regular fa-circle-info',
            'color' => 'text-blue-500',
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-200',
            'text' => 'text-blue-700',
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
                            class="text-gray-400 hover:text-gray-600 transition">
                        <i class="fa-regular fa-xmark text-lg"></i>
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