<x-layout>
@php 
    // ვიღებთ მიმდინარე ენის თარგმანს. 
    // თუ Controller-ში სწორად გაქვს გაფილტრული, პირველივე ელემენტი იქნება საჭირო ენა.
    $translate = $post->translations->first(); 
@endphp

<div class="max-w-4xl mx-auto px-4 py-12">
    {{-- სტატიის სექცია --}}
    <article class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-12">
        <div class="p-8 md:p-12">
            <div class="flex items-center gap-2 mb-6">
                <span class="bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg border border-blue-100">
                    {{ $post->category }}
                </span>
                @if($post->is_edited) 
                    <span class="text-gray-400 text-[10px] font-bold uppercase tracking-wider italic">
                        • {{ __('messages.edited') }}
                    </span>
                @endif
            </div>

            <h1 class="text-4xl md:text-5xl font-black text-gray-900 leading-tight mb-6 tracking-tight">
                {{ $translate->title ?? 'No title' }}
            </h1>

            <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-50">
                <div class="h-12 w-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg">
                    {{ mb_substr($post->user->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-gray-900 font-bold leading-none">{{ $post->user->name }}</p>
                    <p class="text-gray-400 text-xs mt-1">{{ $post->created_at->diffForHumans() }}</p>
                </div>
            </div>

            {{-- სურათი თარგმანიდან (რადგან მიგრაციაში მანდ გაქვს) --}}
            @if($translate && $translate->image)
                <div class="mb-10 rounded-2xl overflow-hidden shadow-lg shadow-gray-200">
                    <img src="{{ asset('storage/' . $translate->image) }}" class="w-full h-auto object-cover max-h-[500px]" alt="{{ $translate->title }}">
                </div>
            @endif

            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed font-serif tracking-normal">
                {!! nl2br(e($translate->description ?? '')) !!}
            </div>
        </div>
    </article>

    {{-- კომენტარების სექცია --}}
    <section class="space-y-8">
        <h3 class="text-2xl font-black text-gray-800 border-b border-gray-100 pb-4">
            💬 {{ __('messages.comments') }} 
            <span class="text-sm font-bold bg-gray-100 text-gray-500 px-2 py-1 rounded-md">{{ $post->comments->count() }}</span>
        </h3>

        {{-- კომენტარის დაწერა --}}
        @auth
            <form action="{{ route('comments.store', [app()->getLocale(), $post->id]) }}" method="POST" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-4">
                @csrf
                <textarea name="content" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none text-gray-700 resize-none" placeholder="{{ __('messages.comment_placeholder') }}" required></textarea>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-black transition shadow-lg shadow-blue-100 transform active:scale-95">
                        {{ __('messages.send') }}
                    </button>
                </div>
            </form>
        @endauth

        {{-- კომენტარების სია (რეკურსიული წყობით) --}}
        <div class="space-y-6">
            {{-- გამოგვაქვს მხოლოდ მთავარი კომენტარები --}}
            @foreach($post->comments->where('parent_id', null) as $comment)
                <div class="group bg-gray-50 p-6 rounded-2xl border border-transparent hover:border-gray-200 hover:bg-white transition duration-300">
                    
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                {{ mb_substr($comment->user->name, 0, 1) }}
                            </div>
                            <span class="text-sm font-black text-gray-800">{{ $comment->user->name }}</span>
                        </div>
                        <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>

                    <p class="text-gray-700 leading-relaxed">{{ $comment->content }}</p>

                    <div class="mt-4 flex items-center gap-4">
                        <button onclick="document.getElementById('reply-{{ $comment->id }}').classList.toggle('hidden')" class="text-xs font-black text-blue-500 hover:text-blue-700 uppercase tracking-tighter">
                            {{ __('messages.reply') }}
                        </button>
                    </div>

                    {{-- პასუხის ფორმა (Hidden) --}}
                    <form id="reply-{{ $comment->id }}" action="{{ route('comments.store', [app()->getLocale(), $post->id]) }}" method="POST" class="hidden mt-4 space-y-2">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <textarea name="content" class="w-full p-3 rounded-xl border border-gray-200 text-sm outline-none focus:ring-2 focus:ring-blue-100" placeholder="{{ __('messages.reply_placeholder') }}" required></textarea>
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-[10px] font-bold uppercase">{{ __('messages.send') }}</button>
                    </form>

                    {{-- პასუხების (Replies) გამოტანა --}}
                    @if($comment->replies->count() > 0)
                        <div class="ml-10 mt-6 space-y-4 border-l-2 border-gray-100 pl-6">
                            @foreach($comment->replies as $reply)
                                <div class="bg-white p-4 rounded-xl border border-gray-50 shadow-sm relative">
                                    {{-- პატარა ისარი ვიზუალისთვის --}}
                                    <div class="absolute -left-6 top-5 w-4 h-0.5 bg-gray-100"></div>
                                    
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-xs font-black text-gray-800">{{ $reply->user->name }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $reply->content }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
</div>
</x-layout>