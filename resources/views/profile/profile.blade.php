  

<div class="profile-container" style="max-width: 800px; margin: 40px auto; padding: 20px; font-family: sans-serif; border: 1px solid #eee; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
    

    <hr style="margin: 40px 0 20px 0;">
    <h3>🔔 შეტყობინებები</h3>
    <div style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; background: #fafafa; margin-bottom: 30px;">
        @php $unreadNotifications = Auth::user()->unreadNotifications; @endphp
        
        @forelse($unreadNotifications as $notification)
            <div style="padding: 15px; border-bottom: 1px solid #eee; background: #f0f7ff; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="display: block; font-weight: 500; color: #333;">
                        {{ $notification->data['message'] ?? 'ახალი შეტყობინება' }}
                    </span>
                    <small style="color: #888;">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
                
                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" style="background: white; border: 1px solid #ced4da; cursor: pointer; border-radius: 4px; padding: 5px 10px; font-size: 14px; transition: 0.2s;" onmouseover="this.style.background='#e9ecef'" onmouseout="this.style.background='white'">
                        ✅ წაკითხვა
                    </button>
                </form>
            </div>
        @empty
            <div style="padding: 20px; text-align: center; color: #999;">ახალი შეტყობინებები არ არის.</div>
        @endforelse
    </div>
    <!-- ნოთიფიკაციების დასასრული -->

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>მოგესალმებით, {{ Auth::user()->name }}!</h1>
        <a href="{{ route('logout') }}" style="color: red; text-decoration: none; font-weight: bold;">გამოსვლა</a>
    </div>
    
    <p>თქვენი როლი: <span style="background: #e9ecef; padding: 2px 8px; border-radius: 4px;">{{ Auth::user()->role->name ?? 'User' }}</span></p>

    <hr style="margin: 20px 0;">

    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        @if(Auth::user()->isAdmin())
            <h3 style="margin-top: 0;">🛠️ ადმინისტრატორის პანელი</h3>
            <a href="{{ route('admin.users') }}" style="color: #007bff; text-decoration: none;">👤 მომხმარებლების მართვა</a>
        @elseif(Auth::user()->isModerator())
            <h3 style="margin-top: 0;">⚖️ მოდერატორის პანელი</h3>
            <a href="{{ route('moderator.dashboard') }}" style="color: #007bff; text-decoration: none;">📝 პოსტების მართვა (Pending)</a>
        @else
            <h3 style="margin-top: 0;">📄 მომხმარებლის მენიუ</h3>
            <p style="color: #666; margin-bottom: 0;">თქვენ შეგიძლიათ განათავსოთ და მართოთ თქვენი სტატიები.</p>
        @endif
    </div>

    <div style="margin: 20px 0;">
        <a href="{{ route('posts.create') }}" 
           style="background-color: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
           + ახალი სტატიის დამატება
        </a>
    </div>

    <h3 style="margin-top: 40px;">📚 ჩემი სტატიები</h3>
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="background: #f4f4f4; text-align: left;">
                <th style="padding: 12px; border: 1px solid #ddd;">სათაური</th>
                <th style="padding: 12px; border: 1px solid #ddd;">სტატუსი</th>
                <th style="padding: 12px; border: 1px solid #ddd;">თარიღი</th>
                <th style="padding: 12px; border: 1px solid #ddd;">მოქმედება</th>
                <th style="padding: 12px; border: 1px solid #ddd;">წაშლა</th> 
            </tr>
        </thead>
        <tbody>
            @forelse(Auth::user()->posts as $post)
                <tr>
                    <td style="padding: 12px; border: 1px solid #ddd;">{{ $post->title }}</td>
                    <td style="padding: 12px; border: 1px solid #ddd;">
                        @if($post->status == 'approved')
                            <span style="color: green; font-weight: bold;">✅ Approved</span>
                        @elseif($post->status == 'rejected')
                            <span style="color: red; font-weight: bold;">❌ Rejected</span>
                        @else
                            <span style="color: orange; font-weight: bold;">⏳ Pending</span>
                        @endif
                    </td>
                    <td style="padding: 12px; border: 1px solid #ddd;">{{ $post->created_at->format('d/m/Y') }}</td>
                    <td style="padding: 12px; border: 1px solid #ddd;">
                        <a href="{{ route('posts.edit', $post->id) }}" style="color: #007bff; text-decoration: none; font-weight: bold;">✏️ რედაქტირება</a>
                    </td>
                    <td style="padding: 12px; border: 1px solid #ddd;">
                         <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('დარწმუნებული ხართ?');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: red; background: none; border: none; cursor: pointer; font-weight: bold;">🗑️ წაშლა</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px; color: #999;">თქვენ ჯერ არ გაქვთ სტატიები დამატებული.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
 
    {{-- <div style="border: 1px solid #eee; border-radius: 8px; overflow: hidden;">
        @php $notifications = Auth::user()->notifications ?? collect([]); @endphp
        
        @forelse($notifications as $notification)
            <div style="padding: 15px; border-bottom: 1px solid #eee; background: {{ $notification->read_at ? 'white' : '#f0f7ff' }}">
                <div style="display: flex; justify-content: space-between;">
                    <span>{{ $notification->data['message'] ?? 'შეტყობინება' }}</span>
                    <small style="color: gray;">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
            </div>
        @empty
            <div style="padding: 20px; text-align: center; color: #999;">ახალი შეტყობინებები არ არის.</div>
        @endforelse
    </div> --}}

</div>