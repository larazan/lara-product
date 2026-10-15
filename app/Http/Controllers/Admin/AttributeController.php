<?php

namespace App\Http\Controllers\Admin;

use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
            'attributes' => $attributes,
            'groups' => $groups,
            'search' => $request->only('q'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Attribute::create(
            [
                'group_id' => $request->groupId,
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
        //
        $request->validate([
            'name' => 'required',
        ]);

        $attribute = Attribute::findOrFail($id);

        $attribute->group_id = $request->groupId;
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

    // public function toggle(Attribute $attribute, Request $request)
    // {
    //     $request->validate([
    //         'is_active' => 'required|boolean',
    //     ]);

    //     $attribute->update(['is_active' => $request->is_active]);

    //     return back()->with('success', 'Category status updated successfully!');
    // }

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
