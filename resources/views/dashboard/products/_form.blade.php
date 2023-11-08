<form method="post" action="{{ route('dashboard.products.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <x-form.input label="Product Name" class="form-control-lg" role="input" name="name" />
    </div>
    <div class="form-group">
        <label for="">Category</label>
        <select name="category_id" class="form-control form-select">
            <option value="">Primary Category</option>
            @foreach(App\Models\Category::all() as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="">Description</label>
        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
    </div>
    <div class="form-group">
        <x-form.label id="image">Image</x-form.label>
        <input type="file" name="image" accept="image/*" class="form-control">
    </div>
    <div class="form-group">
        <x-form.input label="Price" name="price" value="{{ old('price') }}" />
    </div>
    <div class="form-group">
        <x-form.input label="Compare Price" name="compare_price" value="{{ old('compare_price') }}" />
    </div>
    <div class="form-group">
        <x-form.input label="Tags" name="tags" value="{{ old('tags') }}" />
    </div>
    <div class="form-group">
        <label for="">Status</label>
        <div>
            <x-form.radio name="status" :checked="old('status', 'active')" :options="['active' => 'Active', 'draft' => 'Draft', 'archived' => 'Archived']" />
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ $button_label ?? 'Save' }}</button>
    </div>
</form>
