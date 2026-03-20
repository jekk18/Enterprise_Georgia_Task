<x-layout> 
<div class="max-w-7xl mx-auto px-4 py-10">
    
    {{-- ჰედერი და ძებნა --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-6">
        <div>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-3">
                <span class="bg-blue-600 text-white p-2 rounded-xl">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </span>
                პოსტების მართვა
            </h2> 
        </div>

        <form action="{{ route('admin.posts') }}" method="GET" class="relative group w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="მოძებნეთ სათაურით..." 
                   class="w-full md:w-80 pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-2xl text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition outline-none shadow-sm">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
             <button type="submit" class="bg-white border border-gray-200 rounded-2xl px-4 py-3 text-sm font-semibold">ძებნა</button>
        </form>
    </div>

    {{-- ცხრილი --}}
    <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-5 px-6 text-[10px] font-black uppercase tracking-[0.15em] text-gray-400">ავტორი</th>
                        <th class="py-5 px-6 text-[10px] font-black uppercase tracking-[0.15em] text-gray-400">პოსტის სათაური</th>
                        <th class="py-5 px-6 text-[10px] font-black uppercase tracking-[0.15em] text-gray-400 text-center">სტატუსი</th>
                        <th class="py-5 px-6 text-[10px] font-black uppercase tracking-[0.15em] text-gray-400 text-center">თარიღი</th>
                        <th class="py-5 px-6 text-[10px] font-black uppercase tracking-[0.15em] text-gray-400 text-right">მოქმედება</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($posts as $post)
                    <tr class="hover:bg-blue-50/30 transition duration-150">
                        <td class="py-5 px-6">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                                <span class="text-sm font-bold text-gray-800">{{ $post->user->name }}</span>
                            </div>
                        </td>
                        <td class="py-5 px-6">
                            <p class="text-sm text-gray-600 font-medium line-clamp-1">{{ $post->title }}</p>
                        </td>
                        <td class="py-5 px-6 text-center">
                            @php
                                $statusClasses = [
                                    'approved' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'pending'  => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'rejected' => 'bg-rose-50 text-rose-600 border-rose-100'
                                ];
                                $statusLabels = [
                                    'approved' => 'დადასტურებული',
                                    'pending'  => 'მოლოდინში',
                                    'rejected' => 'უარყოფილი'
                                ];
                            @endphp
                            <span class="inline-flex px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter border {{ $statusClasses[$post->status] ?? 'bg-gray-50' }}">
                                {{ $statusLabels[$post->status] ?? $post->status }}
                            </span>
                        </td>
                        <td class="py-5 px-6 text-center text-xs font-bold text-gray-400 tracking-tighter">
                            {{ $post->created_at->format('d/m/Y') }}
                        </td>
                        <td class="py-5 px-6 text-right space-x-1">
                            <div class="flex justify-end items-center gap-2">
                                {{-- ნახვა --}}
                                <a href="{{ route('posts.show', $post) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="ნახვა">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>

                                {{-- ხელახალი განხილვა --}}
                                <form action="{{ route('admin.posts.reReview', $post->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('დაუბრუნდეს მოდერაციას?')"
                                            class="p-2 text-orange-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition" title="მოდერაციაზე დაბრუნება">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                    </button>
                                </form>

                                {{-- წაშლა --}}
                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('სამუდამოდ წაიშალოს?')"
                                            class="p-2 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="წაშლა">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-8 px-2">
        {{ $posts->links() }}
    </div>
</div>
</x-layout>