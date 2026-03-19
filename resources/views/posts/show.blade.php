<div class="container" style="max-width: 800px; margin: 40px auto; font-family: sans-serif; padding: 20px;">

    <h1>
        {{ $post->title }}   
       @if($post->is_edited) 
        <small style="color: gray; font-size: 0.5em;">(რედაქტირებულია)</small> 
    @endif
    </h1>
    
    <p>კატეგორია: <strong>{{ $post->category }}</strong> | ავტორი: {{ $post->user->name }}</p>
    <p><small>თარიღი: {{ $post->created_at->format('d/m/Y H:i') }}</small></p>

    @if($post->image)
        <div style="margin: 20px 0;">
            <img src="{{ asset('storage/' . $post->image) }}" style="width: 100%; border-radius: 10px;">
        </div>
    @endif

    <div style="line-height: 1.6; font-size: 1.1rem; margin-bottom: 40px;">
        {{ $post->description }}
    </div>

    <hr>

    <h3>კომენტარები</h3>

    @auth
        <form action="{{ route('comments.store', $post->id) }}" method="POST" style="margin-bottom: 30px;">
            @csrf
            <textarea name="content" rows="3" style="width: 100%; padding: 10px;" placeholder="დაწერეთ კომენტარი..." required></textarea>
            <button type="submit" style="margin-top: 10px; padding: 10px 20px; background: #007bff; color: white; border: none; cursor: pointer;">გაგზავნა</button>
        </form>
    @else
        <p><a href="{{ route('login') }}">შედით სისტემაში</a> კომენტარის დასაწერად.</p>
    @endauth

    @foreach($post->comments as $comment)
        <div style="background: #f4f4f4; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
            <strong>{{ $comment->user->name }}</strong>
            
            @if($comment->is_edited) 
                <span style="color: gray; font-size: 0.8rem;">(რედაქტირებულია)</span> 
            @endif

            <p>{{ $comment->content }}</p>

            @if(Auth::id() === $comment->user_id)
                <div style="font-size: 0.8rem; margin-bottom: 10px;">
                    <button onclick="document.getElementById('edit-{{ $comment->id }}').style.display='block'" style="border:none; background:none; color: blue; cursor:pointer; padding:0;">რედაქტირება</button>
                    |
                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:inline;">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" style="border:none; background:none; color: red; cursor:pointer; padding:0;">წაშლა</button>
                    </form>
                </div>

                <form id="edit-{{ $comment->id }}" action="{{ route('comments.update', $comment->id) }}" method="POST" style="display:none; margin-top: 10px; margin-bottom: 10px;">
                    @csrf 
                    @method('PUT')
                    <textarea name="content" style="width: 100%; padding: 5px;">{{ $comment->content }}</textarea>
                    <button type="submit" style="margin-top: 5px;">განახლება</button>
                </form>
            @endif

            @auth
                <button onclick="document.getElementById('reply-{{ $comment->id }}').style.display='block'" style="font-size: 0.8rem; border:none; background:none; color: green; cursor:pointer; padding:0;">პასუხი</button>
                
                <form id="reply-{{ $comment->id }}" action="{{ route('comments.store', $post->id) }}" method="POST" style="display:none; margin-top: 10px;">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <textarea name="content" style="width: 100%; padding: 5px;" placeholder="უპასუხეთ..." required></textarea>
                    <button type="submit" style="margin-top: 5px;">გაგზავნა</button>
                </form>
            @endauth

            @foreach($comment->replies as $reply)
                <div style="margin-left: 40px; margin-top: 10px; background: white; padding: 10px; border-left: 2px solid #ccc; border-radius: 4px;">
                    <strong>{{ $reply->user->name }}</strong>
                    @if($reply->is_edited) <span style="color: gray; font-size: 0.7rem;">(რედაქტირებულია)</span> @endif
                    <p style="margin: 5px 0;">{{ $reply->content }}</p>
                </div>
            @endforeach
        </div>
    @endforeach

</div>