<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodopress Installer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-2xl bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="bg-blue-600 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">Dodopress Setup</h1>
        </div>
        <div class="p-8">
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif
            
            @yield('content')
        </div>
        <div class="bg-gray-100 px-6 py-4 border-t border-gray-200 text-sm text-gray-500 text-center">
            Dodopress CMS Installation Wizard
        </div>
    </div>
</body>
</html>
