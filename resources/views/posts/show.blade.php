<x-layout>
   <div class="max-w-4xl mx-auto px-4 py-12">
    {{-- სტატიის ჰედერი --}}
    <article class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-12">
        <div class="p-8 md:p-12">
            <div class="flex items-center gap-2 mb-6">
                <span class="bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border border-blue-100">
                    {{ $post->category }}
                </span>
                @if($post->is_edited) 
                    <span class="text-gray-400 text-[10px] font-bold uppercase tracking-wider italic">
                        • რედაქტირებულია
                    </span>
                @endif
            </div>

            <h1 class="text-4xl md:text-5xl font-black text-gray-900 leading-tight mb-6 tracking-tight">
                {{ $post->title }}
            </h1>

            <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-50">
                <div class="h-12 w-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg">
                    {{ mb_substr($post->user->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-gray-900 font-bold leading-none">{{ $post->user->name }}</p>
                    <p class="text-gray-400 text-xs mt-1">{{ $post->created_at->format('d M, Y • H:i') }}</p>
                </div>
            </div>

            @if($post->image)
                <div class="mb-10 rounded-2xl overflow-hidden shadow-lg shadow-gray-200">
                    <img src="{{ asset('storage/' . $post->image) }}" class="w-full h-auto object-cover max-h-[300px]" alt="{{ $post->title }}">
                </div>
            @endif

            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed font-serif tracking-normal">
                {!! nl2br(e($post->description)) !!}
            </div>
        </div>
    </article>

    {{-- კომენტარების სექცია --}}
    <section class="space-y-8">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4">
            <h3 class="text-2xl font-black text-gray-800 tracking-tight flex items-center gap-2">
                💬 კომენტარები
                <span class="text-sm font-bold bg-gray-100 text-gray-500 px-2 py-1 rounded-md">{{ $post->comments->count() }}</span>
            </h3>
        </div>

        {{-- კომენტარის დაწერა --}}
        @auth
            <form action="{{ route('comments.store', $post->id) }}" method="POST" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                @csrf
                <textarea name="content" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none text-gray-700 resize-none" placeholder="გაგვიზიარეთ თქვენი აზრი..." required></textarea>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-black transition shadow-lg shadow-blue-100 transform active:scale-95">
                        გაგზავნა
                    </button>
                </div>
            </form>
        @else
            <div class="bg-blue-50 p-6 rounded-2xl border border-blue-100 text-center">
                <p class="text-blue-800 font-medium">
                    <a href="{{ route('login') }}" class="underline font-black hover:text-blue-600">შედით სისტემაში</a> კომენტარის დასაწერად.
                </p>
            </div>
        @endauth

        {{-- კომენტარების სია --}}
        <div class="space-y-6">
            @foreach($post->comments as $comment)
                <div class="group">
                    <div class="bg-gray-50 p-6 rounded-2xl border border-transparent group-hover:border-gray-200 group-hover:bg-white transition duration-300">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs">
                                    {{ mb_substr($comment->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="text-sm font-black text-gray-800">{{ $comment->user->name }}</span>
                                    @if($comment->is_edited) <span class="text-[10px] text-gray-400 italic ml-1">(რედაქტირებულია)</span> @endif
                                </div>
                            </div>
                            
                            @if(Auth::id() === $comment->user_id)
                                <div class="flex gap-3 text-[11px] font-bold uppercase tracking-wider">
                                    <button onclick="document.getElementById('edit-{{ $comment->id }}').classList.toggle('hidden')" class="text-blue-500 hover:text-blue-700">რედაქტირება</button>
                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600">წაშლა</button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <p class="text-gray-700 leading-relaxed">{{ $comment->content }}</p>

                        {{-- პასუხის ღილაკი --}}
                        @auth
                            <div class="mt-4 flex items-center gap-4">
                                <button onclick="document.getElementById('reply-{{ $comment->id }}').classList.toggle('hidden')" class="text-xs font-black text-gray-400 hover:text-green-600 transition flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    პასუხი
                                </button>
                            </div>
                        @endauth

                        {{-- რედაქტირების ფორმა (Hidden) --}}
                        <form id="edit-{{ $comment->id }}" action="{{ route('comments.update', $comment->id) }}" method="POST" class="hidden mt-4 space-y-2">
                            @csrf @method('PUT')
                            <textarea name="content" class="w-full p-3 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-blue-100 outline-none">{{ $comment->content }}</textarea>
                            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-xs font-bold">განახლება</button>
                        </form>

                        {{-- პასუხის ფორმა (Hidden) --}}
                        <form id="reply-{{ $comment->id }}" action="{{ route('comments.store', $post->id) }}" method="POST" class="hidden mt-4 space-y-2">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <textarea name="content" class="w-full p-3 rounded-lg border border-blue-100 text-sm outline-none focus:ring-4 focus:ring-blue-50" placeholder="უპასუხეთ..." required></textarea>
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-xs font-bold">გაგზავნა</button>
                        </form>
                    </div>

                    {{-- პასუხები (Replies) --}}
                    @foreach($comment->replies as $reply)
                        <div class="ml-12 mt-4 flex gap-4">
                            <div class="h-1 bg-gray-100 w-4 mt-4 rounded-full"></div>
                            <div class="bg-white p-4 rounded-2xl border border-gray-100 flex-grow shadow-sm">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-black text-gray-800">{{ $reply->user->name }}</span>
                                    @if($reply->is_edited) <span class="text-[9px] text-gray-400 italic">(რედაქტირებულია)</span> @endif
                                </div>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ $reply->content }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>
</div>
</x-layout>