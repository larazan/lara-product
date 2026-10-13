<?php

namespace App\Http\Controllers\Admin;

use App\Models\CategoryArticle;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryArticleController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = 10;
        $sort = 'asc';
        $categories = CategoryArticle::when($request->q, function($query, $q){
            $query->where('name', 'like', '%'.$q.'%');
        })
        // ->when(request('sort'), function ($q) {
        //     $direction = request('direction', 'asc');
        //     $q->orderBy(request('sort'), $direction);
        // })
         ->paginate($page)
        ->withQueryString();

        $catParent = CategoryArticle::whereNull('parent_id')->get();

        return Inertia::render('Admin/CategoryArticle/Index', [
           'page' => $page,
            'categories' => $categories,
            'parentOption' => $catParent,
            'search' => $request->only('q'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug'    => 'nullable|string|max:255|unique:category_articles,slug',
        ]);

        // If user leaves slug empty → auto slug from Spatie
        if (empty($validated['slug'])) {
            unset($validated['slug']); // allow Spatie to generate slug
        }

        $name = $request->name;
        $parentId = $request->parentId;

        CategoryArticle::create(
            $validated
            // [
            //     'name' => $name,
            //     'parent_id' => $parentId,
            // ]
        );

        return redirect()->route('admin.category-article.index')->with('success', 'Category created successfully.');
    }

    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required',
        ]);

        $category = CategoryArticle::findOrFail($id);

        $category->name = $request->name;
        $category->parentId = $request->parent_id;
        $category->status = $request->status;

        $category->update();
        return redirect()->route('admin.category-article.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(string $id)
    {
        //
        $category = CategoryArticle::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully.');
    }

    public function toggle(CategoryArticle $category, Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $category->update(['is_active' => $request->is_active]);

        return back()->with('success', 'Category status updated successfully!');
    }
}
