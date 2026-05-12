<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use App\Models\CategoryArticle;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Image;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

class ArticleController extends Controller
{
    //
     public function index(Request $request)
    {
        $page = 10;
        $sort = 'asc';
        $articles = Article::when($request->q, function($query, $q){
            $query->where('title', 'like', '%'.$q.'%');
        })
        ->with('category')
        // ->when(request('sort'), function ($q) {
        //     $direction = request('direction', 'asc');
        //     $q->orderBy(request('sort'), $direction);
        // })
         ->paginate($page)
        ->withQueryString();

        $categories = CategoryArticle::OrderBy('name', 'asc')->get();

        return Inertia::render('Admin/Article/Index', [
           'page' => $page,
            'articles' => $articles,
            'categories' => $categories,
            'search' => $request->only('q'),
        ]);
    }

    public function create()
    {
        $categories = CategoryArticle::OrderBy('name', 'asc')->get();
        
        return Inertia::render('Admin/Article/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $article = new Article();
        $article->category_id = $request->categoryId;
        $article->author_id = Auth::user()->id;
        $article->title = $request->title;
        $article->body = $request->body;
        $article->published_at = $request->publishedAt;
        $article->article_tags = $request->tags;
       
        // upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            // $image->move('article_image', $uniqueName);

            // WebP
            // Initialize Intervention Image Manager with the desired driver
            // GD is usually a safe bet, ensure it's installed on your server
            $manager = new ImageManager(new Driver());

            // Read the uploaded image from its temporary path
            $img = $manager->read($image->getRealPath());

            // Generate a unique filename for the WebP image
            $fileName = $uniqueName . '.webp';
            // Encode the image to WebP format with desired quality (e.g., 80%)
            $encodedImage = $img->toWebp(quality: 80);

            // Define the storage path (e.g., 'public/uploads')
            $filePath = 'uploads/articles/' . $fileName;

            // Store the WebP image using Laravel's Storage facade
            Storage::disk('public')->put($filePath, $encodedImage);

            $article->image = $filePath;
        }

        $article->save();

        return redirect()->route('admin.article.index')->with('success', 'Article created successfully.');
    }

    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'title' => 'required',
        ]);

        $article = Article::findOrFail($id);

        $article->category_id = $request->categoryId;
        $article->author_id = Auth::user()->id;
        $article->title = $request->title;
        $article->body = $request->body;
        $article->published_at = $request->publishedAt;
        $article->article_tags = $request->tags;
       
        // upload image
        if ($request->hasFile('image')) {
            // delete image
            $this->deleteImage($id);

            $image = $request->file('image');
            $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            // $image->move('article_image', $uniqueName);
           
             // WebP
            // Initialize Intervention Image Manager with the desired driver
            // GD is usually a safe bet, ensure it's installed on your server
            $manager = new ImageManager(new Driver());

            // Read the uploaded image from its temporary path
            $img = $manager->read($image->getRealPath());

            // Generate a unique filename for the WebP image
            $fileName = $uniqueName . '.webp';
            // Encode the image to WebP format with desired quality (e.g., 80%)
            $encodedImage = $img->toWebp(quality: 80);

            // Define the storage path (e.g., 'public/uploads')
            $filePath = 'uploads/articles/' . $fileName;

            // Store the WebP image using Laravel's Storage facade
            Storage::disk('public')->put($filePath, $encodedImage);

            $article->image = $filePath;
        }

        $article->update();

        return redirect()->route('admin.article.index')->with('success', 'Article updated successfully.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096'
        ]);

        $file = $request->file('image');

        $name = time().'.'.$file->extension();

        $path = $file->storeAs('editor', $name, 'public');

        return response()->json([
            'url' => asset('storage/'.$path)
        ]);
    }

    public function destroy(string $id)
    {
        //
        $article = Article::findOrFail($id);
        $article->delete();
        return redirect()->back()->with('success', 'Article deleted successfully.');
    }

    public function deleteImage($id)
    {
        $articleImage = Article::where(['id' => $id])->first();
		$path = 'storage/';

        // ORIGINAL 
        if (File::exists(public_path($path.$articleImage->image))) {
            File::delete(public_path($path.$articleImage->image));
		}
        // return redirect()->route('admin.articles.index')->with('success', 'Image deleted successfully.');
    }

    public function toggleActive(Article $article, Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $article->update(['is_active' => $request->is_active]);

        return back()->with('success', 'Article status updated successfully!');
    }

    public function togglePublish(Article $article, Request $request)
    {
        $request->validate([
            'published' => 'required|boolean',
        ]);

        $article->update(['published' => $request->published]);

        return back()->with('success', 'Article status updated successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:articles,id'],
        ]);

        Article::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected items deleted.');
    }
}
