<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use App\Models\AttributeScore;
use App\Models\Category;
use App\Models\CategoryAttribute;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CategoryAttributeController extends Controller
{
    //
    /**
     * Menampilkan seluruh konfigurasi kategori dan atribut.
     */
    public function index(Request $request)
    {
        $categoryAttributes = CategoryAttribute::query()
            ->with([
                'category:id,name',
                'attribute:id,name,group_id,type,unit',
                'attribute.group:id,name',
                'attributeScore',
            ])
            ->when(
                $request->filled('category_id'),
                fn($query) => $query->where(
                    'category_id',
                    $request->integer('category_id')
                )
            )
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->string('search')->toString();

                    $query->whereHas('category', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })->orWhereHas('attribute', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                }
            )
            ->orderBy('category_id')
            ->orderBy('sort_order')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/CategoryAttribute/IndexTwo', [
            'categoryAttributes' => $categoryAttributes,
            'categories' => Category::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),
            'attributes' => Attribute::query()
                ->with([
                    'attributeGroup:id,name',
                ])
                ->orderBy('name')
                ->get(),
            'filters' => $request->only([
                'category_id',
                'search',
            ]),
        ]);
    }

    /**
     * Form untuk menambahkan atribut ke kategori.
     */
    public function create()
    {
        return Inertia::render('Admin/CategoryAttribute/Create', [
            'categories' => Category::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),

            'attributes' => Attribute::query()
                ->with([
                    'attributeGroup:id,name',
                ])
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * Menyimpan konfigurasi kategori dan atribut.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'attribute_id' => [
                'required',
                'integer',
                'exists:attributes,id',
                Rule::unique('category_attributes', 'attribute_id')
                    ->where('category_id', $request->input('category_id')),
            ],

            'is_required' => ['required', 'boolean'],
            'is_scoring' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],

            'score.weight' => [
                'required_if:is_scoring,1',
                'numeric',
                'min:0.01',
                'max:999999.99',
            ],

            'score.higher_is_better' => [
                'required_if:is_scoring,1',
                'boolean',
            ],

            'score.scoring_method' => [
                'required_if:is_scoring,1',
                Rule::in(['range', 'threshold', 'benchmark', 'manual']),
            ],

            'score.scoring_config' => [
                'nullable',
                'array',
            ],
        ]);

        DB::transaction(function () use ($data) {
            $categoryAttribute = CategoryAttribute::create([
                'category_id' => $data['category_id'],
                'attribute_id' => $data['attribute_id'],
                'is_required' => $data['is_required'],
                'is_scoring' => $data['is_scoring'],
                'sort_order' => $data['sort_order'],
            ]);

            if ($data['is_scoring']) {
                $categoryAttribute->attributeScore()->create([
                    'weight' => $data['score']['weight'],
                    'higher_is_better' =>
                    $data['score']['higher_is_better'],
                    'scoring_method' =>
                    $data['score']['scoring_method'],
                    'scoring_config' =>
                    $data['score']['scoring_config'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('admin.category-attributes.index')
            ->with('success', 'Atribut kategori berhasil ditambahkan.');
    }

    /**
     * Form untuk mengedit konfigurasi.
     */
    public function edit(CategoryAttribute $categoryAttribute)
    {
        $categoryAttribute->load([
            'category:id,name',
            'attribute:id,name,attribute_group_id,type,unit',
            'attributeScore',
        ]);

        return Inertia::render('Admin/CategoryAttribute/Edit', [
            'categoryAttribute' => $categoryAttribute,

            'categories' => Category::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->get(),

            'attributes' => Attribute::query()
                ->with('attributeGroup:id,name')
                ->orderBy('name')
                ->get(),
        ]);
    }

    /**
     * Memperbarui konfigurasi kategori dan atribut.
     */
    public function update(
        Request $request,
        CategoryAttribute $categoryAttribute
    ) {
        $data = $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'attribute_id' => [
                'required',
                'integer',
                'exists:attributes,id',
                Rule::unique('category_attributes', 'attribute_id')
                    ->where('category_id', $request->input('category_id'))
                    ->ignore($categoryAttribute->id),
            ],

            'is_required' => ['required', 'boolean'],
            'is_scoring' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],

            'score.weight' => [
                'required_if:is_scoring,1',
                'numeric',
                'min:0.01',
                'max:999999.99',
            ],

            'score.higher_is_better' => [
                'required_if:is_scoring,1',
                'boolean',
            ],

            'score.scoring_method' => [
                'required_if:is_scoring,1',
                Rule::in(['range', 'threshold', 'benchmark', 'manual']),
            ],

            'score.scoring_config' => [
                'nullable',
                'array',
            ],
        ]);

        DB::transaction(function () use ($data, $categoryAttribute) {
            $categoryAttribute->update([
                'category_id' => $data['category_id'],
                'attribute_id' => $data['attribute_id'],
                'is_required' => $data['is_required'],
                'is_scoring' => $data['is_scoring'],
                'sort_order' => $data['sort_order'],
            ]);

            if ($data['is_scoring']) {
                $categoryAttribute->attributeScore()->updateOrCreate(
                    [],
                    [
                        'weight' => $data['score']['weight'],
                        'higher_is_better' =>
                        $data['score']['higher_is_better'],
                        'scoring_method' =>
                        $data['score']['scoring_method'],
                        'scoring_config' =>
                        $data['score']['scoring_config'] ?? null,
                    ]
                );
            }
        });

        return redirect()
            ->route('admin.category-attributes.index')
            ->with('success', 'Konfigurasi atribut berhasil diperbarui.');
    }

    /**
     * Menghapus hubungan kategori dan atribut.
     */
    public function destroy(CategoryAttribute $categoryAttribute)
    {
        $categoryAttribute->delete();

        return redirect()
            ->route('admin.category-attributes.index')
            ->with('success', 'Atribut berhasil dihapus dari kategori.');
    }
}