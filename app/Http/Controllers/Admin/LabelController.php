<?php

namespace App\Http\Controllers\Admin;

use App\Models\Label;
use App\Models\LabelGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Str;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = 10;
        $sort = 'asc';
        $filters = $request->only([
            'q',
            'group_id',
            'label_type',
            'is_active',
        ]);

        $labels = Label::query()
            ->with('group:id,name,slug')

            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->q;

                $query->where(function ($q) use ($search) {
                    $q->where('slug', 'like', "%{$search}%")
                        ->orWhere('name->en', 'like', "%{$search}%")
                        ->orWhere('name->id', 'like', "%{$search}%");
                });
            })

            ->when($request->filled('group_id'), function ($query) use ($request) {
                $query->where('label_group_id', $request->group_id);
            })

            ->when($request->filled('label_type'), function ($query) use ($request) {
                $query->where('label_type', $request->label_type);
            })

            ->when($request->filled('is_active'), function ($query) use ($request) {
                $query->where('is_active', $request->is_active);
            })

            ->latest()
            ->paginate($page)
            ->withQueryString();

        return Inertia::render('Admin/Label/IndexTwo', [
            'labels' => $labels,
            'pageNumber' => $page,
            'groups' => LabelGroup::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug']),

            'filters' => $filters,

            'locales' => ['en', 'id'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Admin/Label/Form', [
            'label' => null,
            'groups' => $this->getGroups(),
            'locales' => ['en', 'id'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $this->validateLabel($request);

        Label::create($validated);

        return redirect()
            ->route('admin.labels.index')
            ->with('success', 'Label created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Label $label)
    {
        //
        return Inertia::render('Admin/Label/Form', [
            'label' => $label,
            'groups' => $this->getGroups(),
            'locales' => ['en', 'id'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Label $label)
    {
        //
        $validated = $this->validateLabel($request, $label);

        $label->update($validated);

        return redirect()
            ->route('admin.labels.index')
            ->with('success', 'Label updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label)
    {
        //
        $label->delete();

        return redirect()
            ->route('admin.labels.index')
            ->with('success', 'Label deleted successfully.');
    }

    public function toggle(Label $label, Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $label->update(['is_active' => $request->is_active]);

        return back()->with('success', 'Label status updated successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:label_groups,id'],
        ]);

        Label::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected items deleted.');
    }

    /**
     * Validation rules.
     */
    private function validateLabel(
        Request $request,
        ?Label $label = null
    ): array {

        return $request->validate([
            'label_group_id' => [
                'required',
                'exists:label_groups,id',
            ],

            'name' => [
                'required',
                'array',
            ],

            'name.en' => [
                'required',
                'string',
                'max:150',
            ],

            'name.id' => [
                'required',
                'string',
                'max:150',
            ],

            'slug' => [
                'required',
                'string',
                'max:180',
                'alpha_dash',
                Rule::unique('labels', 'slug')
                    ->ignore($label?->id),
            ],

            'description' => [
                'nullable',
                'array',
            ],

            'description.en' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'description.id' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'icon' => [
                'nullable',
                'string',
                'max:255',
            ],

            'label_type' => [
                'required',
                Rule::in([
                    'marketing',
                    'certification',
                    'regulatory',
                    'safety',
                ]),
            ],

            'severity' => [
                'required',
                Rule::in([
                    'info',
                    'low',
                    'medium',
                    'high',
                    'critical',
                ]),
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ]);
    }

    /**
     * Get active label groups.
     */
    private function getGroups()
    {
        return LabelGroup::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($group) {
                return [
                    'id' => $group->id,
                    'slug' => $group->slug,
                    'name' => $group->getTranslations('name'),
                ];
            });
    }
}
