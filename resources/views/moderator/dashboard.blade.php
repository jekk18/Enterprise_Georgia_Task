<h1>მოდერატორის პანელი</h1>
<p>აქ ხედავთ პოსტებს, რომლებიც ელოდებიან დადასტურებას:</p>

@if(session('success'))
    <div style="color: green; padding: 10px; border: 1px solid green; margin-bottom: 10px;">
        {{ session('success') }}
    </div>
@endif

<div class="notifications-section" style="margin-bottom: 30px;">
    <h4>🔔 ახალი მოთხოვნები</h4>
    
    @forelse(Auth::user()->unreadNotifications as $notification) 
        <div style="padding: 10px; background: #fff3cd; border: 1px solid #ffeeba; margin-bottom: 5px; display: flex; justify-content: space-between;">
            
            
            <span>
                {{ $notification->data['message'] }}  
                @if(isset($notification->data['author']))
                    <strong>(ავტორი: {{ $notification->data['author'] }})</strong>
                @endif
            </span>
            
            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                @csrf
                <button type="submit">OK</button>
            </form>
        </div>
    @empty
        <p>ახალი შეტყობინებები არ არის.</p>
    @endforelse
</div>

<table border="1" cellpadding="10" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background: #f4f4f4;">
            <th>ავტორი</th>
            <th>სათაური</th>
            <th>კატეგორია</th>
            <th>აღწერა</th>
            <th>ფოტო</th>
            <th>მოქმედება</th>
        </tr>
    </thead>
    <tbody>
        @forelse($pendingPosts as $post)
            <tr>
                <td>{{ $post->user->name }}</td>
                <td>{{ $post->title }}</td>
                <td>{{ $post->category }}</td>
                <td>{{ Str::limit($post->description, 50) }}</td>
                <td>
                    @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" width="100">
                    @else
                        ფოტო არაა
                    @endif
                </td>
                <td>
                    <form action="{{ route('moderator.updateStatus', $post->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" style="background: green; color: white; cursor: pointer;">Approve</button>
                    </form>

                    <form action="{{ route('moderator.updateStatus', $post->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" style="background: red; color: white; cursor: pointer;">Reject</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center;">ამჟამად დასამტკიცებელი პოსტები არ არის.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<br>
<a href="{{ route('profile') }}">უკან პროფილზე</a>