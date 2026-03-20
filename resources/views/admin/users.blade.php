<x-layout> 
    <div class="max-w-7xl mx-auto"> 
        <h1 class="text-center text-4xl font-bold text-gray-700 mt-4">
            მომხმარებლების მართვა
        </h1>
        {{-- წარმატების შეტყობინება
        @if(session('success'))
            <div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
        @endif --}}

        {{-- შეცდომის შეტყობინება (მაგალითად, თუ ერთადერთი ადმინია) --}}
        @if(session('error'))
            <div style="color: red; padding: 10px; border: 1px solid red; margin-bottom: 15px;">
                {{ session('error') }}
            </div>
        @endif
            
        <a href="{{ route('profile') }}" class="p-4 text-sm/6 font-bold text-blue-600 flex items-center gap-1" style="width: fit-content">← უკან პროფილზე</a>


        <div class="shadow-lg rounded-lg overflow-hidden ">
            <table class="w-full table-fixed">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">სახელი</th>
                        <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">ელ-ფოსტა</th>
                        <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">მიმდინარე როლი</th>
                        <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">როლის შეცვლა</th> 
                    </tr>
                </thead> 
                <tbody class="bg-white">
                    @foreach($users as $user)
                        <tr>
                            <td class="py-4 px-6 border-b border-gray-200">{{ $user->name }}</td>
                            <td class="py-4 px-6 border-b border-gray-200 truncate">{{ $user->email }}</td>
                            <td class="py-4 px-6 border-b border-gray-200">
                                <strong>{{ $user->role->name }}</strong>
                                @if(Auth::id() === $user->id)
                                    <small>(თქვენ)</small>
                                @endif
                            </td>
                            <td class="py-4 px-6 border-b border-gray-200"> 
                                {{-- თუ ეს იუზერი არ არის სისტემაში შესული ადმინი, მაშინ გამოაჩინე ფორმა --}}
                                @if(Auth::id() !== $user->id)
                                    <form action="{{ route('admin.updateRole', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH') 
                                        <select name="role_id" >
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <button type="submit" class="ml-3 mr-3 text-sm bg-blue-500 hover:bg-blue-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline" onclick="return confirm('ნამდვილად გსურთ როლის შეცვლა?')">
                                            შენახვა
                                        </button>
                                    </form>
                                @else
                                    <div style="color: #666; font-style: italic; font-size:13px;">ცვლილება შეუძლებელია</div> 
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> 
</x-layout>