<x-layout> 
  <div class="max-w-4xl mx-auto px-4 py-12">
    {{-- უკან დაბრუნება --}}
    <div class="mb-6">
        <a href="{{ route('profile') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            უკან პროფილზე
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden">
        <div class="bg-gray-50/50 p-8 border-b border-gray-100">
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">✍️ ახალი სტატიის განთავსება</h2>
            <p class="text-gray-500 text-sm mt-1">შეავსეთ ფორმა და გაგზავნეთ მოდერაციაზე გადასამოწმებლად.</p>
        </div>

        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- სათაური --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="text-sm font-bold text-gray-700 ml-1">სტატიის სათაური</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="მაგ: ტექნოლოგიური სიახლეები 2026..." required 
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none text-gray-700">
                    @error('title') <p class="text-red-500 text-xs mt-1 font-medium italic ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- კატეგორია --}}
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700 ml-1">კატეგორია</label>
                    <div class="relative">
                        <select name="category" required 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none appearance-none bg-white text-gray-700">
                            <option value="">აირჩიეთ კატეგორია</option>
                            <option value="პოლიტიკა">პოლიტიკა</option>
                            <option value="ტექნოლოგიები">ტექნოლოგიები</option>
                            <option value="სპორტი">სპორტი</option>
                            <option value="გართობა">გართობა</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        </div>
                    </div>
                </div> 
                {{-- ფოტო ატვირთვა --}}
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700 ml-1">მთავარი ფოტო</label>
                    <input type="file" name="image" accept="image/*" 
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer text-sm text-gray-500">
                    @error('image') <p class="text-red-500 text-xs mt-1 font-medium italic ml-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- აღწერა --}}
            <div class="space-y-2">
                <label class="text-sm font-bold text-gray-700 ml-1">სტატიის შინაარსი</label>
                <textarea name="description" rows="8" placeholder="დაწერეთ თქვენი სტატიის ტექსტი აქ..." required 
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none text-gray-700 resize-none">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1 font-medium italic ml-1">{{ $message }}</p> @enderror
            </div>

            {{-- ღილაკი --}}
            <div class="pt-4 border-t border-gray-50">
                <button type="submit" 
                    class="w-full md:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-lg shadow-blue-200 transition-all transform active:scale-95 flex items-center justify-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    გაგზავნა მოდერაციაზე
                </button>
            </div>
        </form>
    </div>
</div>
</x-layout>