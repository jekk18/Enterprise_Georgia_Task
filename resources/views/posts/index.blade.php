<x-layout>

  <div class="max-w-7xl mx-auto px-4 py-12">
    {{-- სათაურის სექცია --}}
    <div class="mb-12 border-b border-gray-100 pb-8 flex justify-between items-end">
        <div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight">ყველა სტატია</h1>
            <p class="text-gray-500 mt-2 font-medium">აღმოაჩინეთ საინტერესო ისტორიები და სიახლეები</p>
        </div>
        {{-- <span class="text-sm font-bold text-blue-600 bg-blue-50 px-4 py-2 rounded-full">
            სულ: {{ $posts->count() }} სტატია
        </span> --}}
    </div>

    {{-- Grid კონტეინერი --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($posts as $post)
            <div class="group bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300 overflow-hidden flex flex-col">
                
                {{-- სურათის სექცია --}}
                <div class="relative h-56 overflow-hidden">
                    @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" 
                             class="w-full h-full object-cover transition duration-500 group-hover:scale-110" 
                             alt="{{ $post->title }}">
                    @else
                        <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-300">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    
                    {{-- კატეგორიის Badge (თუ გაქვს ბაზაში) --}}
                    @if($post->category)
                        <div class="absolute top-4 left-4">
                            <span class="bg-white/90 backdrop-blur-sm text-gray-800 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg shadow-sm">
                                {{ $post->category }}
                            </span>
                        </div>
                    @endif
                </div>

                {{-- კონტენტის სექცია --}}
                <div class="p-6 flex flex-col flex-grow">
                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-blue-600 transition duration-300 line-clamp-2 leading-tight mb-3">
                        {{ $post->title }}
                    </h3>

                    {{-- ავტორის ინფორმაცია --}}
                    <div class="flex items-center gap-3 mt-auto pt-4 border-t border-gray-50">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                            {{ mb_substr($post->user->name, 0, 1) }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold text-gray-700">{{ $post->user->name }}</span>
                            <span class="text-[10px] text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    {{-- ქვედა ზოლი: კომენტარები და ღილაკი --}}
                    <div class="flex justify-between items-center mt-6">
                        <div class="flex items-center text-gray-400 gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <span class="text-xs font-bold">{{ $post->comments_count }}</span>
                        </div>

                        <a href="{{ route('posts.show', $post->id) }}" 
                           class="text-sm font-black text-blue-600 hover:text-blue-800 flex items-center gap-1 group/btn transition">
                            სრულად 
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover/btn:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

</x-layout>