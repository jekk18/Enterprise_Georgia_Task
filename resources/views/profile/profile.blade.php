<x-layout>  
<div class="max-w-6xl mx-auto px-4 py-10 space-y-8">
    
    {{-- ზედა სექცია: მისალმება და სტატუსი --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ __('messages.welcome') }}, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-500 mt-1 flex items-center gap-2">
                {{ __('messages.your_role') }}: 
                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border border-blue-100">
                    {{ Auth::user()->role->name ?? 'User' }}
                </span>
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('posts.create', app()->getLocale()) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-bold transition shadow-lg shadow-green-100 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('messages.new_post') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- მარცხენა სვეტი: ნოთიფიკაციები და პანელები --}}
        <div class="lg:col-span-1 space-y-8">
            
            {{-- პანელების ბლოკი --}}
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-200">
                @if(Auth::user()->isAdmin())
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">🛠️ {{ __('messages.admin') }}</h3>
                    <a href="{{ route('admin.users', app()->getLocale()) }}" class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-200 hover:border-blue-500 transition group text-gray-700 font-medium">
                        {{ __('messages.manage_users') }}
                        <span class="text-blue-500 group-hover:translate-x-1 transition">&rarr;</span>
                    </a>
                @elseif(Auth::user()->isModerator())
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">⚖️ {{ __('messages.moderator') }}</h3>
                    <a href="{{ route('moderator.dashboard', app()->getLocale()) }}" class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-200 hover:border-blue-500 transition group text-gray-700 font-medium">
                        {{ __('messages.manage_posts') }}
                        <span class="text-blue-500 group-hover:translate-x-1 transition">&rarr;</span>
                    </a>
                @endif
            </div>

            {{-- ნოთიფიკაციები --}}
            <div id="notifications" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-50 bg-gray-50/50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        🔔 {{ __('messages.notifications') }}
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </h3>
                </div>
                <div class="divide-y divide-gray-50 max-h-[400px] overflow-y-auto">
                    @forelse(Auth::user()->unreadNotifications as $notification)
                        <div class="p-4 hover:bg-blue-50/30 transition flex justify-between items-start gap-3">
                            <div>
                                <p class="text-sm text-gray-800 leading-snug">{{ $notification->data['message'] ?? __('messages.new_notification') }}</p>
                                <span class="text-[11px] text-gray-400 mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            <form action="{{ route('notifications.read', ['locale' => app()->getLocale(), 'id' => $notification->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-1 hover:bg-white rounded border border-transparent hover:border-gray-200 transition text-green-600" title="{{ __('messages.mark_as_read') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="p-10 text-center">
                            <p class="text-gray-400 text-sm">{{ __('messages.no_notifications') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- მარჯვენა სვეტი: ჩემი სტატიები --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50">
                    <h3 class="text-xl font-bold text-gray-800 font-bold">📚 {{ __('messages.my_posts') }}</h3>
                </div>

                @if(session('success'))
                    <div class="mx-6 mt-4 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="p-4 text-xs font-bold uppercase text-gray-400 tracking-wider">{{ __('messages.title') }}</th>
                                <th class="p-4 text-xs font-bold uppercase text-gray-400 tracking-wider text-center">{{ __('messages.status') }}</th>
                                <th class="p-4 text-xs font-bold uppercase text-gray-400 tracking-wider text-right">{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse(Auth::user()->posts as $post)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="p-4">
                                        <div class="text-sm font-semibold text-gray-800">{{ $post->title }}</div>
                                        <div class="text-[11px] text-gray-400 mt-0.5">{{ $post->created_at->format('d M, Y') }}</div>
                                    </td>
                                    <td class="p-4 text-center">
                                        @if($post->status == 'approved')
                                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-md text-[10px] font-black uppercase border border-green-200">✅ {{ __('messages.approved') }}</span>
                                        @elseif($post->status == 'rejected')
                                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-md text-[10px] font-black uppercase border border-red-200">❌ {{ __('messages.rejected') }}</span>
                                        @else
                                            <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-md text-[10px] font-black uppercase border border-orange-200">⏳ {{ __('messages.pending') }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <div class="flex justify-end items-center gap-4">
                                            <a href="{{ route('posts.edit', ['locale' => app()->getLocale(), 'post' => $post->id]) }}" class="text-blue-600 hover:text-blue-800 transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('posts.destroy', ['locale' => app()->getLocale(), 'post' => $post->id]) }}" method="POST" onsubmit="return confirm('{{ __('messages.are_you_sure') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="p-12 text-center text-gray-400 italic">{{ __('messages.no_posts_yet') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</x-layout>