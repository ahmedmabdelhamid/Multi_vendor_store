<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $products = Product::with(['category', 'store'])->paginate();
        return view('dashboard.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // You can fetch necessary data like categories and tags if needed
        return view('dashboard.products.create');

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust image validation rules as needed
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric',
            'compare_price' => 'nullable|numeric',
            'status' => 'required|in:active,draft,archived',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Add a unique slug for the product based on its name
        $validatedData['slug'] = Str::slug($validatedData['name']);

        // Add the 'store_id' if it's associated with the product (adjust this as needed)
        $validatedData['store_id'] = 1; // Replace with the actual store ID

        // Create and save the product
        $product = Product::create($validatedData);

        // Redirect to the product index page with a success message
        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product created');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);

        $tags = implode(',', $product->tags()->pluck('name')->toArray());

        return view('dashboard.products.edit', compact('product', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {

        $product->update( $request->except('tags') );


        $tags = json_decode($request->post('tags'));
        $tag_ids = [];

        $saved_tags = Tag::all();

        //dd($request->post('tags'));

        if (is_null($tags)) {
            $tags = [];
        }

        foreach ($tags as $item) {

            $slug = Str::slug($item->value);
            $tag = $saved_tags->where('slug', $slug)->first();
            if (!$tag) {
                $tag = Tag::create([
                    'name' => $item->value,
                    'slug' => $slug,
                ]);
            }
            $tag_ids[] = $tag->id;
        }

        $product->tags()->sync($tag_ids);

        return redirect()->route('dashboard.products.index')
            ->with('success', 'Product updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
