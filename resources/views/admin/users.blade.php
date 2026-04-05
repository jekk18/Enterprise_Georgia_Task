<x-layout>  
        {{-- წარმატების შეტყობინება
        @if(session('success'))
            <div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
        @endif --}}

        {{-- შეცდომის შეტყობინება (მაგალითად, თუ ერთადერთი ადმინია) --}}
     @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <div role="alert" class="rounded-md border-l-4 border-red-300 bg-red-100 p-4 text-red-700 opacity-75">
            <p class="font-bold">{{ __('messages.error_title') }}</p>
            <p>{{ session('error') }}</p>
        </div>
    </div>
@endif
            
<div class="max-w-7xl mx-auto px-4 py-10">
     
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">👥 {{ __('messages.manage_users') }}</h1> 
        </div> 

        <a href="{{ route('profile', app()->getLocale()) }}" class="group flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('messages.back_to_profile') }}
        </a>
        
    </div>
       {{-- მომხმარებლების ექსპორტი --}}
        <div class="mb-6 text-right">
            <a href="{{ route('admin.export.users', app()->getLocale()) }}" 
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl text-sm font-bold transition shadow-lg shadow-blue-100 group">
                <svg class="w-5 h-5 text-blue-200 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                {{ __('messages.export_users') }}
            </a>
        </div>
        {{-- მომხმარებლების ექსპორტი --}}
 
    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-5 px-6 text-xs font-black uppercase tracking-widest text-gray-400">{{ __('messages.user') }}</th>
                        <th class="py-5 px-6 text-xs font-black uppercase tracking-widest text-gray-400">{{ __('messages.email') }}</th>
                        <th class="py-5 px-6 text-xs font-black uppercase tracking-widest text-gray-400">{{ __('messages.current_role') }}</th>
                        <th class="py-5 px-6 text-xs font-black uppercase tracking-widest text-gray-400 text-right">{{ __('messages.action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($users as $user)
                        <tr class="hover:bg-blue-50/30 transition duration-200 group">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-gray-100 to-gray-200 flex items-center justify-center text-gray-600 font-bold text-sm border border-gray-200">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-gray-800 group-hover:text-blue-700 transition">
                                        {{ $user->name }}
                                        @if(Auth::id() === $user->id)
                                            <span class="ml-1 text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full uppercase">{{ __('messages.you') }}</span>
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-500 font-medium">
                                {{ $user->email }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold border 
                                    {{ $user->role->name === 'admin' ? 'bg-purple-50 text-purple-600 border-purple-100' : 
                                       ($user->role->name === 'moderator' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-gray-50 text-gray-600 border-gray-100') }}">
                                    {{ $user->role->name }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                @if(Auth::id() !== $user->id)
                                    <form action="{{ route('admin.updateRole', ['locale' => app()->getLocale(), 'user' => $user->id]) }}" method="POST" class="flex items-center justify-end gap-2">
                                        @csrf
                                        @method('PATCH')
                                        
                                        <div class="relative">
                                            <select name="role_id" class="appearance-none pl-3 pr-8 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition cursor-pointer">
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            </div>
                                        </div>

                                        <button type="submit" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-black uppercase tracking-tighter px-4 py-2 rounded-xl transition shadow-md shadow-blue-100 active:scale-95"
                                            onclick="return confirm('{{ __('messages.confirm_role_change') }}')">
                                            {{ __('messages.save') }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] font-bold text-gray-300 italic uppercase tracking-widest">{{ __('messages.not_allowed') }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> 
</div>
</x-layout>