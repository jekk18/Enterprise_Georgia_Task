<x-layout>
  <div class="max-w-4xl mx-auto px-4 py-12">
    {{-- ზედა ნავიგაცია --}}
    <div class="mb-6 flex justify-between items-center">
        {{-- დაემატა ენა პროფილის ლინკზე --}}
        <a href="{{ route('profile', app()->getLocale()) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('messages.back_to_profile') }}
        </a>
        <span class="text-xs font-bold uppercase tracking-widest text-gray-400 bg-gray-100 px-3 py-1 rounded-full">
            ID: #{{ $post->id }}
        </span>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-gray-100/50 border border-gray-100 overflow-hidden"> 
        <div class="bg-gray-50/50 p-8 border-b border-gray-100">
            <h2 class="text-2xl font-black text-gray-800 tracking-tight">✏️ {{ __('messages.edit_post') }}</h2>
            <p class="text-gray-500 text-sm mt-1">{{ __('messages.edit_subtitle') }}</p>
        </div>

        {{-- მთავარი შესწორება ფორმის action-ში: locale და post --}}
        <form action="{{ route('posts.update', ['locale' => app()->getLocale(), 'post' => $post->id]) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- სათაური --}}
                <div class="md:col-span-2 space-y-2">
                    <label class="text-sm font-bold text-gray-700 ml-1">{{ __('messages.post_title') }}</label>
                    <input type="text" name="title" value="{{ old('title', $post->title) }}" required 
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none text-gray-700 font-medium">
                    @error('title') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                {{-- კატეგორია --}}
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700 ml-1">{{ __('messages.category') }}</label>
                    <div class="relative">
                        <select name="category" required 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none appearance-none bg-white text-gray-700 cursor-pointer">
                            {{-- აქ მნიშვნელოვანია, რომ კატეგორიის მნიშვნელობები ბაზაში ერთ ენაზე ინახებოდეს (მაგ: ინგლისურად), ხოლო გამოსაჩენად თარგმნო --}}
                            <option value="Politics" {{ $post->category == 'Politics' ? 'selected' : '' }}>{{ __('messages.cat_politics') }}</option>
                            <option value="Tech" {{ $post->category == 'Tech' ? 'selected' : '' }}>{{ __('messages.cat_tech') }}</option>
                            <option value="Sports" {{ $post->category == 'Sports' ? 'selected' : '' }}>{{ __('messages.cat_sports') }}</option>
                            <option value="Entertainment" {{ $post->category == 'Entertainment' ? 'selected' : '' }}>{{ __('messages.cat_entertainment') }}</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                        </div>
                    </div>
                </div>

                {{-- ფოტოს შეცვლა --}}
                <div class="space-y-2">
                    <label class="text-sm font-bold text-gray-700 ml-1">{{ __('messages.new_photo') }}</label>
                    <input type="file" name="image" accept="image/*" 
                        class="w-full px-3 py-2.5 rounded-xl border border-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition cursor-pointer text-sm text-gray-500">
                </div>
            </div>

            {{-- მიმდინარე ფოტოს ვიზუალი --}}
            @if($post->image)
                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100 inline-block">
                    <label class="text-[10px] uppercase tracking-widest font-black text-gray-400 block mb-2 text-center">{{ __('messages.current_photo') }}</label>
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $post->image) }}" class="h-32 w-48 object-cover rounded-lg shadow-sm border border-white">
                    </div>
                </div>
            @endif

            {{-- აღწერა --}}
            <div class="space-y-2">
                <label class="text-sm font-bold text-gray-700 ml-1">{{ __('messages.post_content') }}</label>
                <textarea name="description" rows="10" required 
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none text-gray-700 resize-none leading-relaxed">{{ old('description', $post->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>

            {{-- ღილაკები --}}
            <div class="pt-6 border-t border-gray-50 flex flex-col md:flex-row items-center gap-4">
                <button type="submit" 
                    class="w-full md:w-auto px-10 py-4 bg-green-600 hover:bg-green-700 text-white font-black rounded-2xl shadow-lg shadow-green-100 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                    {{ __('messages.save_changes') }}
                </button>
                
                <a href="{{ route('profile', app()->getLocale()) }}" 
                    class="w-full md:w-auto px-10 py-4 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-2xl transition-all text-center">
                    {{ __('messages.cancel') }}
                </a>
            </div>
        </form>
    </div>
</div>
</x-layout>