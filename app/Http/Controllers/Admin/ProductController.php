<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\CategoryAttribute;
use App\Models\Company;
use App\Models\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
}
