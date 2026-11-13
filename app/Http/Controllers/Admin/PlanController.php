<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;


class PlanController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        $page = 10;
        $sort = 'asc';
        $plans = Plan::when($request->q, function ($query, $q) {
            $query->where('name', 'like', '%' . $q . '%');
        })
            // ->when(request('sort'), function ($q) {
            //     $direction = request('direction', 'asc');
            //     $q->orderBy(request('sort'), $direction);
            // })
            ->paginate($page)
            ->withQueryString();

        return Inertia::render('Admin/Plan/Index', [
            'page' => $page,
            'plans' => $plans,
            'search' => $request->only('q'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'price' => 'required',
        ]);

        $name = $request->name;
        Plan::create([
            'name' => $name,
            'description' => $request->description,
            'price' => $request->price,
            'discount' => $request->discount,
            'duration_days' => $request->duration_days,
            'features' => $request->features,
        ]);

        return redirect()->route('admin.plan.index')->with('success', 'Plan created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required',
            'price' => 'required',
        ]);

        $plan = Plan::findOrFail($id);

        $plan->name = $request->name;
        $plan->description = $request->description;
        $plan->price = $request->price;
        $plan->discount = $request->discount;
        $plan->duration_days = $request->duration_days;
        $plan->features = $request->features;

        $plan->update();
        return redirect()->route('admin.plan.index')->with('success', 'Plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $plan = Plan::findOrFail($id);
        $plan->delete();
        return redirect()->back()->with('success', 'Plan deleted successfully.');
    }

    public function toggle(Plan $plan, Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $plan->update(['is_active' => $request->is_active]);

        return back()->with('success', 'Plan status updated successfully!');
    }
}
