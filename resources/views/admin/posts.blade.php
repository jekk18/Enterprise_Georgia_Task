<x-layout>
    <div class="max-w-7xl mx-auto p-6  ">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">ყველა პოსტის მართვა</h2>
            
            <form action="{{ route('admin.posts') }}" method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="ძებნა ..." 
                    class="border border-gray-700 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm font-semibold">ძებნა</button>
            </form>
        </div>

        <div class="bg-white shadow-sm border border-gray-100 rounded-xl overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500">ავტორი</th>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500">სათაური</th>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500">სტატუსი</th>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500">თარიღი</th>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500 text-center">მოქმედება</th>
                        <th class="p-4 text-xs font-bold uppercase text-gray-500 text-right">წაშლა</th> 
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($posts as $post)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-sm font-medium text-gray-700">{{ $post->user->name }}</td>
                        <td class="p-4 text-sm text-gray-600">{{ Str::limit($post->title, 40) }}</td>
                        <td class="p-4 text-sm">
                            @if($post->status === 'approved')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-md text-xs font-bold">Approved</span>
                            @elseif($post->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-md text-xs font-bold">Pending</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded-md text-xs font-bold">Rejected</span>
                            @endif
                        </td>
                        <td class="p-4 text-sm text-gray-500">{{ $post->created_at->format('d M, Y') }}</td>
                        <td class="p-4  flex justify-center items-center gap-x-3">
                            <form action="{{ route('admin.posts.reReview', $post->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('დარწმუნებული ხართ, რომ გსურთ პოსტი დაუბრუნოთ მოდერატორს?')"
                                        class="text-orange-600 hover:text-orange-900 font-bold bg-orange-50 px-3 py-1 rounded-md transition border border-orange-200 cursor-pointer text-xs">
                                    🔄 ხელახალი განხილვა
                                </button>
                            </form>
                            <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:underline text-sm font-bold">ნახვა</a>
                        </td>
                        <td class="p-4 text-right text-sm text-gray-500">
                            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('ნამდვილად გსურთ პოსტის სამუდამოდ წაშლა?')" 
                                        class="text-red-600 hover:text-red-900 font-bold bg-red-50 px-3 py-1 rounded-md text-xs cursor-pointer">
                                    🗑️ წაშლა
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </div>
</x-layout>