<?php

namespace App\Http\Controllers\Admin;

use App\Models\AttributeGroup;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AttributeGroupController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = 10;
        $sort = 'asc';
        $groups = AttributeGroup::when($request->q, function ($query, $q) {
            $query->where('name', 'like', '%' . $q . '%');
        })
            // ->when(request('sort'), function ($q) {
            //     $direction = request('direction', 'asc');
            //     $q->orderBy(request('sort'), $direction);
            // })
            ->paginate($page)
            ->withQueryString();

        $categories = Category::whereNull('parent_id')->get();

        return Inertia::render('Admin/AttributeGroup/Index', [
            'page' => $page,
            'categories' => $categories,
            'groups' => $groups,
            'search' => $request->only('q'),
            'locales' => config('app.locales'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|array',
        ]);

        $attribute_group = new AttributeGroup();
        $attribute_group->category_id = $request->categoryId;
        $attribute_group->name = $request->name;
        $attribute_group->label = $request->label;
        $attribute_group->order = $request->order;
        $attribute_group->icon = $request->icon;
        $attribute_group->score_weight = $request->scoreWeight;

        $attribute_group->save();

        return redirect()->route('admin.attribute-groups.index')->with('success', 'Attribute Group created successfully.');
    }

    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required|array',
        ]);

        $attribute_group = AttributeGroup::findOrFail($id);

        $attribute_group->category_id = $request->categoryId;
        $attribute_group->name = $request->name;
        $attribute_group->label = $request->label;
        $attribute_group->order = $request->order;
        $attribute_group->icon = $request->icon;
        $attribute_group->score_weight = $request->scoreWeight;

        $attribute_group->update();
        return redirect()->route('admin.attribute-groups.index')->with('success', 'Attribute Group updated successfully.');
    }

    public function destroy(string $id)
    {
        //
        $attribute_group = AttributeGroup::findOrFail($id);
        $attribute_group->delete();
        return redirect()->back()->with('success', 'Attribute Group deleted successfully.');
    }

    public function toggle(Category $attribute_group, Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $attribute_group->update(['is_active' => $request->is_active]);

        return back()->with('success', 'Attribute Group status updated successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:attribute_groups,id'],
        ]);

        AttributeGroup::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected items deleted.');
    }
}
