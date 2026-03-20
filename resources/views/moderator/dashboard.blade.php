<x-layout>
   <div class="max-w-7xl mx-auto px-4 py-10 space-y-8">

    {{-- Header სექცია --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">⚖️ მოდერატორის პანელი</h1>
            <p class="text-gray-500 mt-1">აქ ხედავთ პოსტებს, რომლებიც ელოდებიან დადასტურებას ან ხელახალ განხილვას.</p>
        </div>
        <a href="{{ route('profile') }}" class="flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            უკან პროფილზე
        </a>
    </div>

    {{-- წარმატების შეტყობინება --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm animate-fade-in-down">
            <div class="flex items-center">
                <div class="flex-shrink-0 text-green-500">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- ნოთიფიკაციების ბლოკი --}}
    <div class="grid grid-cols-1 gap-4">
        <h4 class="text-lg font-bold text-gray-700 flex items-center gap-2 px-2">
            🔔 ახალი მოთხოვნები
            @if(Auth::user()->unreadNotifications->count() > 0)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    {{ Auth::user()->unreadNotifications->count() }}
                </span>
            @endif
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse(Auth::user()->unreadNotifications as $notification) 
                <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl flex justify-between items-start gap-4 shadow-sm hover:shadow-md transition">
                    <div class="space-y-1">
                        <p class="text-sm text-amber-900 font-medium leading-snug">
                            {{ $notification->data['message'] }}
                        </p>
                        @if(isset($notification->data['author']))
                            <span class="text-xs text-amber-700 block italic">ავტორი: {{ $notification->data['author'] }}</span>
                        @endif
                        <span class="text-[10px] text-amber-600 block">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-white hover:bg-amber-100 text-amber-800 border border-amber-300 px-3 py-1 rounded-lg text-xs font-bold transition">
                            OK
                        </button>
                    </form>
                </div>
            @empty
                <div class="col-span-full py-6 text-center bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl">
                    <p class="text-gray-400 italic">ახალი შეტყობინებები არ არის.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- პოსტების ცხრილი --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="p-4 text-xs font-bold uppercase text-gray-500 tracking-wider">ავტორი & ფოტო</th>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500 tracking-wider">სათაური</th>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500 tracking-wider">კატეგორია</th>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500 tracking-wider">აღწერა</th>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500 tracking-wider text-right">მოქმედება</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($pendingPosts as $post)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 flex-shrink-0">
                                        @if($post->image)
                                            <img src="{{ asset('storage/' . $post->image) }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center text-gray-300">
                                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <span class="text-sm font-bold text-gray-800">{{ $post->user->name }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="text-sm font-semibold text-gray-700 leading-tight block max-w-[200px]">{{ $post->title }}</span>
                            </td>
                            <td class="p-4">
                                <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full text-[11px] font-bold border border-blue-100 uppercase">
                                    {{ $post->category }}
                                </span>
                            </td>
                            <td class="p-4 text-sm text-gray-500">
                                {{ Str::limit($post->description, 50) }}
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <form action="{{ route('moderator.updateStatus', $post->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-lg transition shadow-sm hover:shadow-green-100 group" title="დადასტურება">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </form>

                                    <form action="{{ route('moderator.updateStatus', $post->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 p-2 rounded-lg transition border border-red-100 group" title="უარყოფა">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-16 w-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/></svg>
                                    </div>
                                    <p class="text-gray-400 font-medium">ამჟამად დასამტკიცებელი პოსტები არ არის.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</x-layout>