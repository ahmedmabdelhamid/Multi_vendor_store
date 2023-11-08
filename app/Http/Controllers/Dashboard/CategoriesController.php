<?php

namespace App\Http\Controllers\Dashboard;
use Validator;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Storage;


class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $request = request();

        $categories = Category::with('parent')
        /*leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
        ->select([
            'categories.*',
            'parents.name as parent_name'
        ])*/
        //->select('categories.*')
        //->selectRaw('(SELECT COUNT(*) FROM products WHERE AND status = 'active' AND category_id = categories.id) as products_count')
        //->addSelect(DB::raw('(SELECT COUNT(*) FROM products WHERE category_id = categories.id) as products_count'))
        ->withCount([
            'products as products_number' => function($query) {
                $query->where('status', '=', 'active');
            }
        ])
        ->filter($request->query())->orderBy('categories.name')->paginate();
        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = Category::all();
        $category = new Category();
        return view('dashboard.categories.create', compact('parents', 'category'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate(Category::rules());

        $request->merge([
            'slug' => Str::slug($request->post('name'))
        ]);

        $data = $request->except('image');
        $data['image'] = $this->uploadImgae($request);


        $category = Category::create($data);

        return Redirect::route('dashboard.categories.index')->with('success' , 'Category Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('dashboard.categories.show', [
            'category' => $category
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $category = Category::findOrFail($id);
        } catch (Exception $e) {
            return redirect()->route('dashboard.categories.index')
                ->with('info', 'Record not found!');
        }

        // SELECT * FROM categories WHERE id <> $id
        // AND (parent_id IS NULL OR parent_id <> $id)
        $parents = Category::where('id', '<>', $id)
            ->where(function($query) use ($id) {
                $query->whereNull('parent_id')
                      ->orWhere('parent_id', '<>', $id);
            })
            ->get();

        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, $id)
    {

        // $request->validate(Category::rules($id));

        $category = Category::findOrFail($id);
        $old_image = $category->image;
        $data = $request->except('image');

        $new_image = $this->uploadImgae($request);

        if ($new_image) {
            $data['image'] = $new_image;
        }

         $category->update($data);

         if ($old_image && $new_image) {
            Storage::disk('public')->delete($old_image);
         }

         return Redirect::route('dashboard.categories.index')->with('success' , 'Category Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Category::destroy($id);

           $category = Category::findOrFail($id);
           $category->delete();


        return Redirect::route('dashboard.categories.index')->with('success' , 'Category Deleted');

    }

    protected function uploadImgae(Request $request)
    {
        if (!$request->hasFile('image')) {
            return;
        }

        $file = $request->file('image'); // UploadedFile Object

        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);
        return $path;
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->paginate();
        return view('dashboard.categories.trash', compact('categories'));
    }

    public function restore(Request $request, $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('dashboard.categories.trash')
            ->with('succes', 'Category restored!');
    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        return redirect()->route('dashboard.categories.trash')
            ->with('succes', 'Category deleted forever!');
    }
}
