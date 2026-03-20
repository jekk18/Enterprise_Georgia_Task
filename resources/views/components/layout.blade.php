<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js']) 
    @else
        <!-- Tailwind CSS v4 Play CDN -->
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
        
        <!-- დამატებითი სტილები (თუ დაგჭირდა) -->
        <style type="text/tailwindcss">
            @theme {
                --font-sans: 'Instrument Sans', ui-sans-serif, system-ui;
            }
        </style>
    @endif
</head>
    <body class="mx-auto w-full bg-linear-to-r from-indigo-100 from-10% via-sky-100 via-30% to-emerald-100 to-90% text-slate-700 ">
   
        <header class="bg-white border-b border-gray-100">
                <nav aria-label="Global" class="mx-auto flex max-w-7xl items-center justify-between p-6 "> 
                    <div class="flex lg:flex-1 items-center gap-x-12">
                        <a href="/" class="-m-1.5 p-1.5 font-bold text-xl tracking-tight text-gray-900">
                            Blog<span class="text-blue-600">APP</span>
                        </a> 
                        <div class="hidden lg:flex lg:gap-x-8">  
                            {{-- მხოლოდ მოდერატორისთვის --}}
                            @auth
                            @if(Auth::user()->isModerator())
                                <a href="{{ route('moderator.dashboard') }}" class="text-sm/6 font-bold text-orange-600 flex items-center gap-1">
                                <span>⚖️</span> მოდერაცია
                                </a>
                            @endif

                            {{-- მხოლოდ ადმინისთვის --}}
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.posts') }}" class="text-sm/6 font-bold text-blue-600 flex items-center gap-1">
                                    <span>📝</span> ყველა პოსტი
                                </a>
                                <a href="{{ route('admin.users') }}" class="text-sm/6 font-bold text-blue-600 flex items-center gap-1">
                                <span>🛠️</span> ადმინ პანელი
                                </a>
                            @endif
                            @endauth
                        </div>
                    </div>

                    <div class="flex flex-1 justify-end items-center gap-x-6">
                        @auth
                            <a href="{{ url('/profile') }}" class="text-sm/6 font-semibold text-gray-900 hover:text-blue-600">
                                {{ Auth::user()->name }}
                            </a> 
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="text-sm/6 font-semibold text-red-500 cursor-pointer hover:text-red-700">
                                    გამოსვლა <span aria-hidden="true">&rarr;</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm/6 font-semibold text-gray-900 hover:text-blue-600">
                                შესვლა
                            </a>
                            <a href="{{ route('register') }}" class="rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                რეგისტრაცია
                            </a>
                        @endauth
                    </div> 
                </nav>
        </header>

       @if(session('success'))
            <div class="my-8 rounded-md border-l-4 border-green-300 bg-green-100 p-4 text-green-700 opacity-75">
                <p class="font-bold">Success!</p>
                <p>{{ session('success') }}</p>
            </div>
       @endif 
       
       @if (session('error'))
            <div role="alert"
            class="my-8 rounded-md border-l-4 border-red-300 bg-red-100 p-4 text-red-700 opacity-75">
            <p class="font-bold">Error!</p>
            <p>{{ session('error') }}</p>
            </div>
        @endif 

        {{ $slot }} 
    </body>
</html>
