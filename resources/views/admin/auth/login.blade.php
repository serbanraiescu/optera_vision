<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentificare Admin | Optera Vision</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dynamic Styles -->
    <style>
        :root {
            --color-primary: {{ setting('brand.primary_color', '#0F3D24') }};
            --color-primary-dark: {{ setting('brand.secondary_color', '#164E2D') }};
        }
        body {
            font-family: 'Inter', sans-serif;
        }
        h1, h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50/50 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white border border-slate-100 rounded-3xl shadow-xl shadow-slate-950/[0.02] p-8 md:p-10">
        <!-- Logo Header -->
        <div class="flex flex-col items-center text-center gap-3 mb-10">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center border border-emerald-100/50 shadow-sm">
                <svg class="w-6 h-6 text-emerald-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h1 class="text-xl font-bold tracking-tight text-slate-900 leading-none">OPTERA VISION</h1>
                <span class="text-[10px] font-extrabold tracking-[0.25em] text-emerald-800 leading-none mt-2">SECURE LOGIN</span>
            </div>
        </div>

        <!-- Login Form -->
        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Form Validation Error Alerts -->
            @if ($errors->any())
                <div class="p-4 bg-red-50/80 border border-red-100 rounded-xl text-xs text-red-800 leading-relaxed font-semibold">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Email Field -->
            <div class="flex flex-col gap-1.5">
                <label for="email" class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Adresă Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus class="h-12 w-full px-4 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-700/50 focus:border-emerald-800 text-sm font-semibold transition-all">
            </div>

            <!-- Password Field -->
            <div class="flex flex-col gap-1.5">
                <div class="flex justify-between items-center">
                    <label for="password" class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Parolă</label>
                </div>
                <input type="password" name="password" id="password" required class="h-12 w-full px-4 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-700/50 focus:border-emerald-800 text-sm font-semibold transition-all">
            </div>

            <!-- Remember checkbox -->
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-emerald-800 rounded border-slate-350 focus:ring-emerald-700">
                <label for="remember" class="ml-2 text-xs font-bold text-slate-550 select-none cursor-pointer">Ține-mă minte pe acest dispozitiv</label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full h-12 inline-flex items-center justify-center text-sm font-bold text-white bg-[var(--color-primary)] hover:bg-[var(--color-primary-dark)] rounded-xl transition-all duration-200 shadow-md shadow-emerald-950/10 focus:outline-none focus:ring-2 focus:ring-emerald-700">
                Autentificare
            </button>
        </form>
    </div>

</body>
</html>
