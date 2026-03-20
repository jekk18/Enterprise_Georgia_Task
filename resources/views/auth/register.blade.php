<x-layout>
   <div class="h-screen bg-linear-to-r from-indigo-100 from-10% via-sky-100 via-30% to-emerald-100 to-90% flex justify-center items-center w-full px-4 overflow-y-auto py-10">
    
    <form action="{{ route('register') }}" method="POST" class="w-full max-w-md">
        @csrf
        
        <div class="bg-white px-8 py-10 rounded-2xl shadow-2xl w-full">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">რეგისტრაცია</h1>
                <p class="text-gray-500 text-sm mt-2">შექმენით ახალი ანგარიში</p>
            </div>

            <div class="space-y-5">
                {{-- სახელი --}}
                <div>
                    <div class="flex items-center border-2 py-3 px-4 rounded-xl focus-within:border-blue-500 transition group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <input class="pl-3 outline-none border-none w-full text-gray-700 bg-transparent" type="text" name="name" value="{{ old('name') }}" placeholder="თქვენი სახელი" required autofocus/>
                    </div>
                    @error('name')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- ელ-ფოსტა --}}
                <div>
                    <div class="flex items-center border-2 py-3 px-4 rounded-xl focus-within:border-blue-500 transition group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                        <input class="pl-3 outline-none border-none w-full text-gray-700 bg-transparent" type="email" name="email" value="{{ old('email') }}" placeholder="ელ-ფოსტა" required/>
                    </div>
                    @error('email')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- პაროლი --}}
                <div>
                    <div class="flex items-center border-2 py-3 px-4 rounded-xl focus-within:border-blue-500 transition group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                        <input class="pl-3 outline-none border-none w-full text-gray-700 bg-transparent" type="password" name="password" placeholder="პაროლი" required/>
                    </div>
                    @error('password')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                {{-- პაროლის დადასტურება --}}
                <div>
                    <div class="flex items-center border-2 py-3 px-4 rounded-xl focus-within:border-blue-500 transition group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                        </svg>
                        <input class="pl-3 outline-none border-none w-full text-gray-700 bg-transparent" type="password" name="password_confirmation" placeholder="გაიმეორეთ პაროლი" required/>
                    </div>
                </div>
            </div>

            <button type="submit" class="mt-8 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-200 transition-all duration-300 transform hover:-translate-y-1">
                რეგისტრაცია
            </button>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    უკვე გაქვთ ანგარიში? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-bold">შესვლა</a>
                </p>
            </div>
        </div>
    </form>
</div>
</x-layout>