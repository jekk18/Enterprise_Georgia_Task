<div class="container">
    <h2>ახალი სტატიის განთავსება</h2>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="margin-bottom: 15px;">
            <label>სათაური:</label><br>
            <input type="text" name="title" value="{{ old('title') }}" required style="width: 100%;">
            @error('title') <span style="color:red">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label>კატეგორია:</label><br>
            <select name="category" required>
                <option value="">აირჩიეთ კატეგორია</option>
                <option value="პოლიტიკა">პოლიტიკა</option>
                <option value="ტექნოლოგიები">ტექნოლოგიები</option>
                <option value="სპორტი">სპორტი</option>
                <option value="გართობა">გართობა</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label>აღწერა:</label><br>
            <textarea name="description" rows="10" style="width: 100%;" required>{{ old('description') }}</textarea>
            @error('description') <span style="color:red">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 15px;">
            <label>ფოტო:</label><br>
            <input type="file" name="image" accept="image/*">
            @error('image') <span style="color:red">{{ $message }}</span> @enderror
        </div>

        <button type="submit" style="padding: 10px 20px; background: green; color: white; border: none; cursor: pointer;">
            გაგზავნა მოდერაციაზე
        </button>
    </form>
</div>