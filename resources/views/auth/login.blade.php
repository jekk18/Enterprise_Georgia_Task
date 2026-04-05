
<x-layout>
<div class="h-screen bg-linear-to-r from-indigo-100 from-10% via-sky-100 via-30% to-emerald-100 to-90% flex justify-center items-center w-full px-4">
    
    <form method="POST" action="{{ route('login', app()->getLocale()) }}" class="w-full max-w-sm">
        @csrf
        
        <div class="bg-white px-10 py-10 rounded-2xl shadow-2xl w-full">
            <div class="space-y-6">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-800">{{ __('messages.login_title') }}</h1>  
                </div>

                <hr class="border-gray-100">

                {{-- Email Input --}}
                <div>
                    <div class="flex items-center border-2 py-3 px-4 rounded-xl mb-1 focus-within:border-blue-500 transition group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                        <input class="pl-3 outline-none border-none w-full text-gray-700 bg-transparent" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="{{ __('messages.email_placeholder') }}" 
                               required 
                               autofocus/>
                    </div>
                    @error('email')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password Input --}}
                <div>
                    <div class="flex items-center border-2 py-3 px-4 rounded-xl mb-1 focus-within:border-blue-500 transition group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        <input class="pl-3 outline-none border-none w-full text-gray-700 bg-transparent" 
                               type="password" 
                               name="password" 
                               placeholder="{{ __('messages.password_placeholder') }}" 
                               required/>
                    </div>
                    @error('password')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div> 

            {{-- Login Button --}}
            <button type="submit" class="mt-8 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-200 transition-all duration-300 transform hover:-translate-y-1">
                {{ __('messages.login_button') }}
            </button>

            <div class="mt-6 text-center">
                <a href="{{ route('register', app()->getLocale()) }}" class="text-sm text-blue-600 hover:underline font-medium">
                    {{ __('messages.no_account_register') }}
                </a>
            </div>
        </div> 
    </form>
</div>
</x-layout>