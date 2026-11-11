<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\CategoryLabel;
use App\Models\Label;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CategoryLabelController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = 10;
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('slug')
            ->get()
            ->map(fn($category) => [
                'id' => $category->id,
                'slug' => $category->slug,
                'name' => $category->getTranslations('name'),
            ]);

        $categoryId = $request->integer('category_id');

        if (!$categoryId) {
            $categoryId = $categories->first()['id'] ?? null;
        }

        $selectedCategory = $categoryId
            ? Category::find($categoryId)
            : null;

        $categoryLabels = $selectedCategory
            ? CategoryLabel::query()
            ->with(['label.group'])
            ->where('category_id', $selectedCategory->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn($item) => [
                'id' => $item->id,
                'label_id' => $item->label_id,
                'label' => [
                    'id' => $item->label->id,
                    'name' => $item->label->getTranslations('name'),
                    'slug' => $item->label->slug,
                    'icon' => $item->label->icon,
                    'label_type' => $item->label->label_type,
                    'severity' => $item->label->severity,
                    'group' => $item->label->group
                        ? $item->label->group->getTranslations('name')
                        : null,
                ],
                'is_required' => $item->is_required,
                'is_active' => $item->is_active,
                'sort_order' => $item->sort_order,
            ])
            : collect();

        $availableLabels = Label::query()
            ->with('group')
            ->where('is_active', true)
            ->orderBy('label_group_id')
            ->orderBy('slug')
            ->get()
            ->map(fn($label) => [
                'id' => $label->id,
                'name' => $label->getTranslations('name'),
                'slug' => $label->slug,
                'label_type' => $label->label_type,
                'severity' => $label->severity,
                'group' => $label->group
                    ? $label->group->getTranslations('name')
                    : null,
            ]);

        return Inertia::render('Admin/CategoryLabel/Index', [
            'pageNumber' => $page,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory ? [
                'id' => $selectedCategory->id,
                'name' => $selectedCategory->getTranslations('name'),
                'slug' => $selectedCategory->slug,
            ] : null,
            'categoryLabels' => $categoryLabels,
            'availableLabels' => $availableLabels,
            'locales' => ['en', 'id'],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
            'label_id' => [
                'required',
                'exists:labels,id',
                Rule::unique('category_labels', 'label_id')
                    ->where('category_id', $request->category_id),
            ],
            'is_required' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ], [
            'label_id.unique' => 'Label sudah ditambahkan ke kategori ini.',
        ]);

        CategoryLabel::create($validated);

        return back()->with('success', 'Label berhasil ditambahkan ke kategori.');
    }

    public function update(Request $request, CategoryLabel $categoryLabel)
    {
        $validated = $request->validate([
            'label_id' => [
                'required',
                'exists:labels,id',
                Rule::unique('category_labels', 'label_id')
                    ->where('category_id', $categoryLabel->category_id)
                    ->ignore($categoryLabel->id),
            ],
            'is_required' => ['required', 'boolean'],
            'is_active' => ['required', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ], [
            'label_id.unique' => 'Label sudah digunakan dalam kategori ini.',
        ]);

        $categoryLabel->update($validated);

        return back()->with('success', 'Pengaturan label berhasil diperbarui.');
    }

    public function destroy(CategoryLabel $categoryLabel)
    {
        $categoryLabel->delete();

        return back()->with('success', 'Label berhasil dihapus dari kategori.');
    }
}
