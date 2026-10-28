<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttributeResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AttributeController extends Controller
{
    //type ['text', 'number', 'boolean', 'select']

    public function index(Request $request)
    {
        $page = 10;
        $sort = 'asc';
        $attributes = Attribute::when($request->q, function ($query, $q) {
            $query->where('name', 'like', '%' . $q . '%');
        })
            // ->when(request('sort'), function ($q) {
            //     $direction = request('direction', 'asc');
            //     $q->orderBy(request('sort'), $direction);
            // })
            ->paginate($page)
            ->withQueryString();

        $groups = AttributeGroup::orderBy('name', 'desc')->get();

        return Inertia::render('Admin/Attribute/Index', [
            'page' => $page,
            'attributes' => AttributeResource::collection($attributes),
            'groups' => $groups,
            'search' => $request->only('q'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'group_id' => ['nullable', 'exists:attribute_groups,id'],

            'name' => ['required', 'array'],
            'name.*' => ['nullable', 'string', 'max:255'],

            'label' => ['nullable', 'array'],
            'label.*' => ['nullable', 'string', 'max:255'],

            'description' => ['nullable', 'string'],
            'icon_svg' => ['nullable', 'string'],
            'unit' => ['nullable', 'string', 'max:50'],

            'type' => [
                'required',
                Rule::in([
                    'text',
                    'textarea',
                    'number',
                    'decimal',
                    'boolean',
                    'select',
                    'multiselect',
                    'date',
                    'url',
                ]),
            ],

            'options' => ['nullable', 'array'],
            'options.*.value' => ['required_with:options', 'string', 'max:255'],
            'options.*.label' => ['required_with:options', 'array'],
            'options.*.label.*' => ['nullable', 'string', 'max:255'],

            'validation_rules' => ['nullable', 'array'],
            'validation_rules.min' => ['nullable', 'numeric'],
            'validation_rules.max' => ['nullable', 'numeric'],

            'is_filterable' => ['sometimes', 'boolean'],
            'is_comparable' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        // Pastikan minimal ada satu nama yang terisi.
        if (collect($data['name'] ?? [])->filter(
            fn($value) => filled($value)
        )->isEmpty()) {
            return back()
                ->withErrors(['name' => 'Minimal satu nama atribut harus diisi.'])
                ->withInput();
        }

        // Normalisasi field pilihan.
        if (!in_array($data['type'], ['select', 'multiselect'], true)) {
            $data['options'] = null;
        }

        // Hilangkan nilai min/max yang kosong.
        $data['validation_rules'] = collect(
            $data['validation_rules'] ?? []
        )->filter(fn($value) => $value !== null && $value !== '')
            ->all() ?: null;

        $data['is_filterable'] = $request->boolean('is_filterable');
        $data['is_comparable'] = $request->boolean('is_comparable', true);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        Attribute::create($data);

        return redirect()
            ->route('admin.attributes.index')
            ->with('success', 'Attribute created successfully.');
    }

    public function storeTwo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Attribute::create(
            [
                'group_id' => $request->group_id,
                'name' => $request->name,
                'label' => $request->label,
                'description' => $request->description,
                'unit' => $request->unit,
                'type' => $request->type,
            ]
        );

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute created successfully.');
    }

    public function update(Request $request, string $id)
    {
        $attribute = Attribute::findOrFail($id);

        $data = $request->validate([
            'group_id' => ['nullable', 'exists:attribute_groups,id'],

            'name' => ['required', 'array'],
            'name.*' => ['nullable', 'string', 'max:255'],

            'label' => ['nullable', 'array'],
            'label.*' => ['nullable', 'string', 'max:255'],

            'description' => ['nullable', 'string'],
            'icon_svg' => ['nullable', 'string'],
            'unit' => ['nullable', 'string', 'max:50'],

            'type' => [
                'required',
                Rule::in([
                    'text',
                    'textarea',
                    'number',
                    'decimal',
                    'boolean',
                    'select',
                    'multiselect',
                    'date',
                    'url',
                ]),
            ],

            'options' => ['nullable', 'array'],
            'options.*.value' => ['required_with:options', 'string', 'max:255'],
            'options.*.label' => ['required_with:options', 'array'],
            'options.*.label.*' => ['nullable', 'string', 'max:255'],

            'validation_rules' => ['nullable', 'array'],
            'validation_rules.min' => ['nullable', 'numeric'],
            'validation_rules.max' => ['nullable', 'numeric'],

            'is_filterable' => ['sometimes', 'boolean'],
            'is_comparable' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        // Pastikan minimal satu terjemahan nama terisi.
        if (collect($data['name'])->filter(
            fn($value) => filled($value)
        )->isEmpty()) {
            return back()
                ->withErrors([
                    'name' => 'Minimal satu nama atribut harus diisi.',
                ])
                ->withInput();
        }

        // Options hanya berlaku untuk select dan multiselect.
        if (!in_array($data['type'], ['select', 'multiselect'], true)) {
            $data['options'] = null;
        }

        // Hilangkan nilai min/max kosong.
        $data['validation_rules'] = collect(
            $data['validation_rules'] ?? []
        )->filter(fn($value) => $value !== null && $value !== '')
            ->all() ?: null;

        // Checkbox yang tidak dikirim dianggap false.
        $data['is_filterable'] = $request->boolean('is_filterable');
        $data['is_comparable'] = $request->boolean('is_comparable');
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        $attribute->update($data);

        return redirect()
            ->route('admin.attributes.index')
            ->with('success', 'Attribute updated successfully.');
    }

    public function updateTwo(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required',
        ]);

        $attribute = Attribute::findOrFail($id);

        $attribute->group_id = $request->group_id;
        $attribute->name = $request->name;
        $attribute->label = $request->label;
        $attribute->description = $request->description;
        $attribute->unit = $request->unit;
        $attribute->type = $request->type;

        $attribute->update();

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute updated successfully.');
    }

    public function destroy(string $id)
    {
        //
        $attribute = Attribute::findOrFail($id);
        $attribute->delete();
        return redirect()->back()->with('success', 'Attribute deleted successfully.');
    }

    public function toggle(Attribute $attribute, Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $attribute->update(['is_active' => $request->is_active]);

        return back()->with('success', 'Attribute status updated successfully!');
    }

    public function toggleFilter(Attribute $attribute, Request $request)
    {
        $request->validate([
            'is_filterable' => 'required|boolean',
        ]);

        $attribute->update(['is_filterable' => $request->is_filterable]);

        return back()->with('success', 'Attribute status filter change successfully!');
    }

    // 'sort_order',

    public function toggleCompare(Attribute $attribute, Request $request)
    {
        $request->validate([
            'is_comparable' => 'required|boolean',
        ]);

        $attribute->update(['is_comparable' => $request->is_comparable]);

        return back()->with('success', 'Attribute status compare change successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:attributes,id'],
        ]);

        Attribute::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected items deleted.');
    }
}
