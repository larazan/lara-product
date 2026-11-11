<?php

namespace App\Http\Controllers\Admin;

use App\Models\LabelGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LabelGroupController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = 10;
        $sort = 'asc';
        $groups = LabelGroup::when($request->q, function ($query, $q) {
            $query->where('name', 'like', '%' . $q . '%');
        })
            // ->when(request('sort'), function ($q) {
            //     $direction = request('direction', 'asc');
            //     $q->orderBy(request('sort'), $direction);
            // })
            ->paginate($page)
            ->withQueryString();


        return Inertia::render('Admin/LabelGroup/Index', [
            'pageNumber' => $page,
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

        $label_group = new LabelGroup();
        // $label_group->name = $request->name;
        $label_group->setTranslations('name', $request->name);
        $label_group->setTranslations('description', $request->description);
        $label_group->sort_order = $request->order;

        $label_group->save();

        return redirect()->route('admin.label-groups.index')->with('success', 'Label Group created successfully.');
    }

    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required|array',
        ]);

        $label_group = LabelGroup::findOrFail($id);

        // $label_group->name = $request->name;
        $label_group->setTranslations('name', $request->name);
        $label_group->setTranslations('description', $request->description);
        $label_group->sort_order = $request->order;

        $label_group->update();
        return redirect()->route('admin.label-groups.index')->with('success', 'Label Group updated successfully.');
    }

    public function destroy(string $id)
    {
        //
        $label_group = LabelGroup::findOrFail($id);
        $label_group->delete();
        return redirect()->back()->with('success', 'Label Group deleted successfully.');
    }

    public function toggle(LabelGroup $label_group, Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $label_group->update(['is_active' => $request->is_active]);

        return back()->with('success', 'Label Group status updated successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:label_groups,id'],
        ]);

        LabelGroup::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected items deleted.');
    }
}
