<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryAttribute;
use App\Models\Company;
use App\Models\Country;
use App\Models\Product;
use App\Models\ProductAffiliate;
use App\Models\ProductAttributeValue;
use App\Models\ProductFaq;
use App\Models\ProductImage;
use App\Models\ProductInstruction;
use App\Models\ProductNutrition;
use App\Models\ProductSafetyInstruction;
use App\Models\ProductVariant;
use App\Models\ProductVariantAttributeValue;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class ProductController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = 10;
        $sort = 'asc';
        $products = Product::when($request->q, function ($query, $q) {
            $query->where('name', 'like', '%' . $q . '%');
        })
            ->with('brand', 'category', 'country', 'company')
            // ->when(request('sort'), function ($q) {
            //     $direction = request('direction', 'asc');
            //     $q->orderBy(request('sort'), $direction);
            // })
            ->paginate($page)
            ->withQueryString();

        $brands = Brand::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $countries = Country::orderBy('name', 'asc')->get();
        $companies = Company::orderBy('name', 'asc')->get();


        return Inertia::render('Admin/Product/Index', [
            'page' => $page,
            'products' => $products,
            'brands' => $brands,
            'categories' => $categories,
            'companies' => $companies,
            'countries' => $countries,
            'search' => $request->only('q'),
            'locales' => config('app.locales'),
        ]);
    }

    public function create()
    {
        $locale = app()->getLocale();

        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('slug')
            ->get()
            ->map(fn($category) => [
                'id' => $category->id,
                'name' => $category->getTranslation('name', $locale, false),
                'parent_id' => $category->parent_id,
            ]);

        $catParent = Category::orderBy('name', 'asc')->get();

        return Inertia::render('Admin/Product/Create', [
            'categories' => $categories,
            'parentOption' => $catParent,
            'brands' => Brand::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']),
            'companies' => Company::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']),
            'countries' => Country::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'icon']),
            'locales' => ['en', 'id'],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|array',
        ]);

        $product = new Product();
        $product->user_id = Auth::user()->id;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->company_id = $request->company_id;
        $product->country_id = $request->country_id;

        $product->name = $request->name;
        $product->model_number = $request->model_number;
        $product->description = $request->description;
        $product->year = $request->year;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->metadata = $request->metadata;
        $product->is_featured = $request->is_featured;
        $product->is_active = $request->is_active;

        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required|array',
        ]);

        $product = Product::findOrFail($id);

        $product->user_id = Auth::user()->id;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->company_id = $request->company_id;
        $product->country_id = $request->country_id;

        $product->name = $request->name;
        $product->model_number = $request->model_number;
        $product->description = $request->description;
        $product->year = $request->year;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->metadata = $request->metadata;
        $product->is_featured = $request->is_featured;
        $product->is_active = $request->is_active;

        $product->update();
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(string $id)
    {
        //
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully.');
    }

    public function toggle(Product $product, Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $product->update(['is_active' => $request->is_active]);

        return back()->with('success', 'Product status updated successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:products,id'],
        ]);

        Product::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected items deleted.');
    }

    public function attributes(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ]);

        $locale = app()->getLocale();

        $attributes = CategoryAttribute::query()
            ->where('category_id', $data['category_id'])
            ->whereHas('attribute', function ($query) {
                $query->where('is_active', true);
            })
            ->with('attribute.group')
            ->get()
            ->map(function ($categoryAttribute) use ($locale) {
                $attribute = $categoryAttribute->attribute;

                if (! $attribute) {
                    return null;
                }

                return [
                    'id' => $attribute->id,
                    'slug' => $attribute->slug,

                    // Ambil terjemahan sesuai locale aktif.
                    'name' => $attribute->getTranslation(
                        'name',
                        $locale,
                        false
                    ),

                    'label' => $attribute->getTranslation(
                        'label',
                        $locale,
                        false
                    ),

                    'description' => $attribute->description,
                    'type' => $attribute->type,
                    'unit' => $attribute->unit,

                    // Konfigurasi dinamis dari Attribute.
                    'options' => $attribute->options ?? [],
                    'validation_rules' => $attribute->validation_rules ?? [],

                    // Konfigurasi dari CategoryAttribute.
                    'is_required' => (bool) $categoryAttribute->is_required,

                    // Metadata atribut.
                    'is_filterable' => (bool) $attribute->is_filterable,
                    'is_comparable' => (bool) $attribute->is_comparable,
                    'sort_order' => $attribute->sort_order,

                    'icon_svg' => $attribute->icon_svg,

                    'group' => $attribute->group ? [
                        'id' => $attribute->group->id,
                        'name' => $attribute->group->getTranslation(
                            'name',
                            $locale,
                            false
                        ),
                        'sort_order' => $attribute->group->order ?? 0,
                    ] : null,
                ];
            })
            ->filter()
            ->sortBy('sort_order')
            ->values();

        return response()->json([
            'data' => $attributes,
        ]);
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|array',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        $category = new Category();
        $category->setTranslations('name', $request->name);
        // $category->name = $request->name;
        $category->parent_id = $request->parentId ?? null;

        // upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            // $image->move('image', $uniqueName);

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
            $filePath = 'uploads/categories/' . $fileName;

            // Store the WebP image using Laravel's Storage facade
            Storage::disk('public')->put($filePath, $encodedImage);

            $category->image = $filePath;
        }

        $category->save();

        return redirect()->route('admin.product.create')->with('success', 'Category created successfully.');
    }

    public function storeBrand(Request $request)
    {
        $request->validate([
            'name' => 'required|array',
        ]);

        $brand = new Brand();
        $brand->company_id = $request->company_id;
        $brand->name = $request->name;
        $brand->description = $request->description;
        $brand->website = $request->website;

        // upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            // $image->move('image', $uniqueName);

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
            $filePath = 'uploads/categories/' . $fileName;

            // Store the WebP image using Laravel's Storage facade
            Storage::disk('public')->put($filePath, $encodedImage);

            $brand->logo = $filePath;
        }

        $brand->save();

        return redirect()->route('admin.product.create')->with('success', 'Brand created successfully.');
    }
}
