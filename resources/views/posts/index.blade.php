<h1>ყველა სტატია</h1>

<div class="posts-container">
    @foreach($posts as $post)
        <div style="border: 1px solid #ccc; margin-bottom: 20px; padding: 15px;">
            <h3>{{ $post->title }}</h3>
            
            @if($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" width="200" alt="სურათი">
            @endif

            <p>ავტორი: <strong>{{ $post->user->name }}</strong></p>
            <p>კომენტარები: {{ $post->comments_count }}</p> <a href="{{ route('posts.show', $post->id) }}">სრულად ნახვა</a>
        </div>
    @endforeach
</div>