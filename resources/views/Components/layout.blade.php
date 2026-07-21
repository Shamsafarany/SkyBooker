<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" type="image/png" href="/image.png">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <title>{{ $title ? "SkyBooker | $title" : "SkyBooker"}}</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">
    
    <x-nav />
    <main class="flex-grow pt-28 pb-16">
        <div class="max-w-7xl mx-auto px-6">
                @if (isset($header))
                <div class="text-4xl md:text-5xl font-extrabold text-purple-900 tracking-tight">
                    {{ $header }}
                </div>
                @endif
            {{$slot}}
        </div>
    </main>

    <x-footer/>
</body>
</html>
