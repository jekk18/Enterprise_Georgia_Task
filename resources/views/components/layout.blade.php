<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js']) 
    @else
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
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
                <a href="{{ route('posts.index', app()->getLocale()) }}" class="-m-1.5 p-1.5 font-bold text-xl tracking-tight text-gray-900">
                    Blog<span class="text-blue-600">APP</span>
                </a> 
                <div class="hidden lg:flex lg:gap-x-8">    
                    @auth
                        @if(Auth::user()->isModerator())
                            <a href="{{ route('moderator.dashboard', app()->getLocale()) }}" class="text-sm/6 font-bold text-orange-600 flex items-center gap-1">
                                <span>⚖️</span> {{ __('messages.moderation') }}
                            </a>
                        @endif

                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.posts', app()->getLocale()) }}" class="text-sm/6 font-bold text-blue-600 flex items-center gap-1">
                                <span>📝</span> {{ __('messages.all_posts') }}
                            </a>
                            <a href="{{ route('admin.users', app()->getLocale()) }}" class="text-sm/6 font-bold text-blue-600 flex items-center gap-1">
                                <span>🛠️</span> {{ __('messages.admin_panel') }}
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="flex flex-1 justify-end items-center gap-x-6">
                 <div class="flex flex-1 justify-end items-center gap-x-6">
                    @auth
                        <a href="{{ route('profile', app()->getLocale()) }}" class="text-sm/6 font-semibold text-gray-900 hover:text-blue-600">
                            {{ Auth::user()->name }}
                        </a> 
                        <form method="POST" action="{{ route('logout', app()->getLocale()) }}" class="m-0">
                            @csrf
                            <button type="submit" class="text-sm/6 font-semibold text-red-500 cursor-pointer hover:text-red-700">
                                {{ __('messages.logout') }} <span aria-hidden="true">&rarr;</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login', app()->getLocale()) }}" class="text-sm/6 font-semibold text-gray-900 hover:text-blue-600">
                            {{ __('messages.login') }}
                        </a>
                        <a href="{{ route('register', app()->getLocale()) }}" class="rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            {{ __('messages.register') }}
                        </a>
                    @endauth
                </div> 
                 {{-- ენის გადამრთველი (Language Switcher) --}}
                <div class="flex items-center gap-2 border-l pl-8 border-gray-100">
                    <a href="{{ route(Route::currentRouteName() ?? 'posts.index', array_merge(Route::current() ? Route::current()->parameters() : [], ['locale' => 'ka'])) }}" 
                        class="text-xs font-bold {{ app()->getLocale() == 'ka' ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
                        GE
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route(Route::currentRouteName() ?? 'posts.index', array_merge(Route::current() ? Route::current()->parameters() : [], ['locale' => 'en'])) }}" 
                        class="text-xs font-bold {{ app()->getLocale() == 'en' ? 'text-blue-600' : 'text-gray-400 hover:text-gray-600' }}">
                        EN
                    </a>
                </div> 
            </div>
            
        </nav>
    </header>

    <div class="max-w-7xl mx-auto px-4">
        @if(session('success'))
            <div class="my-8 rounded-md border-l-4 border-green-300 bg-green-100 p-4 text-green-700 opacity-75">
                <p class="font-bold">{{ __('messages.success_title') }}</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif 
        
        @if (session('error'))
            <div role="alert" class="my-8 rounded-md border-l-4 border-red-300 bg-red-100 p-4 text-red-700 opacity-75">
                <p class="font-bold">{{ __('messages.error_title') }}</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif 
    </div>

    {{ $slot }} 
</body>
</html>
