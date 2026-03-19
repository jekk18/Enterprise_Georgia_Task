<div class="container" style="max-width: 600px; margin: 40px auto; font-family: sans-serif;">
    <h2>სტატიის რედაქტირება</h2>

    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') <div style="margin-bottom: 15px;">
            <label>სათაური:</label><br>
            <input type="text" name="title" value="{{ old('title', $post->title) }}" required style="width: 100%; padding: 8px;">
        </div>

        <div style="margin-bottom: 15px;">
            <label>კატეგორია:</label><br>
            <select name="category" required style="width: 100%; padding: 8px;">
                <option value="პოლიტიკა" {{ $post->category == 'პოლიტიკა' ? 'selected' : '' }}>პოლიტიკა</option>
                <option value="ტექნოლოგიები" {{ $post->category == 'ტექნოლოგიები' ? 'selected' : '' }}>ტექნოლოგიები</option>
                <option value="სპორტი" {{ $post->category == 'სპორტი' ? 'selected' : '' }}>სპორტი</option>
                <option value="გართობა" {{ $post->category == 'გართობა' ? 'selected' : '' }}>გართობა</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label>აღწერა:</label><br>
            <textarea name="description" rows="10" style="width: 100%; padding: 8px;" required>{{ old('description', $post->description) }}</textarea>
        </div>

        <div style="margin-bottom: 15px;">
            <label>მიმდინარე ფოტო:</label><br>
            @if($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" width="150" style="margin-bottom: 10px; border-radius: 5px;"><br>
            @endif
            <label>შეცვალეთ ფოტო (არასავალდებულო):</label><br>
            <input type="file" name="image" accept="image/*">
        </div>

        <button type="submit" style="background: #28a745; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 5px;">
            ცვლილებების შენახვა
        </button>
        <a href="{{ route('profile') }}" style="margin-left: 10px; text-decoration: none; color: gray;">გაუქმება</a>
    </form>
</div>