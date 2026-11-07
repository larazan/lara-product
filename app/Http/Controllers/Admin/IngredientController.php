<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IngredientController extends Controller
{
    protected array $locales = ['en', 'id'];
    //
    public function index(Request $request)
    {
        $page = 10;
        $sort = 'asc';
        $ingredients = Ingredient::when($request->q, function ($query, $q) {
            $query->where('name', 'like', '%' . $q . '%');
        })
            // ->when(request('sort'), function ($q) {
            //     $direction = request('direction', 'asc');
            //     $q->orderBy(request('sort'), $direction);
            // })
            ->paginate($page)
            ->withQueryString();


        return Inertia::render('Admin/Ingredient/Index', [
            'pageNumber' => $page,
            'ingredients' => $ingredients,
            'search' => $request->only('q'),
            'locales' => config('app.locales'),
        ]);
    }

    public function storeTwo(Request $request)
    {
        $request->validate([
            'name' => 'required|array',
        ]);

        $ingredient = new Ingredient();
        $ingredient->name = $request->name;
        $ingredient->inci_name = $request->inci_name;
        $ingredient->safety_rating = $request->safety_rating;
        $ingredient->description = $request->description;

        $ingredient->save();

        return redirect()->route('admin.ingredient.index')->with('success', 'Ingredient created successfully.');
    }

    public function updateTwo(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required|array',
        ]);

        $ingredient = Ingredient::findOrFail($id);
        $ingredient->name = $request->name;
        $ingredient->inci_name = $request->inci_name;
        $ingredient->safety_rating = $request->safety_rating;
        $ingredient->description = $request->description;
        $ingredient->update();

        return redirect()->route('admin.ingredient.index')->with('success', 'Ingredient updated successfully.');
    }

    public function destroy(string $id)
    {
        //
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->delete();
        return redirect()->back()->with('success', 'Ingredient deleted successfully.');
    }

    public function toggle(Ingredient $ingredient, Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $ingredient->update(['is_active' => $request->is_active]);

        return back()->with('success', 'Ingredient status updated successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:ingredients,id'],
        ]);

        Ingredient::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected items deleted.');
    }

    /**
     * Store a new ingredient.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:150'],
            'name.id' => ['required', 'string', 'max:150'],

            'inci_name' => ['nullable', 'string', 'max:150'],

            'safety_rating' => [
                'nullable',
                'integer',
                'min:1',
                'max:5',
            ],

            'description' => ['nullable', 'array'],
            'description.en' => ['nullable', 'string'],
            'description.id' => ['nullable', 'string'],
        ]);

        Ingredient::create([
            'name' => $validated['name'],
            'inci_name' => $validated['inci_name'] ?? null,
            'safety_rating' => $validated['safety_rating'] ?? null,
            'description' => $validated['description'] ?? [],
        ]);

        return redirect()
            ->route('admin.ingredients.index')
            ->with('success', 'Ingredient created successfully.');
    }

    /**
     * Update an existing ingredient.
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => ['required', 'array'],
            'name.en' => ['required', 'string', 'max:150'],
            'name.id' => ['required', 'string', 'max:150'],

            'inci_name' => ['nullable', 'string', 'max:150'],

            'safety_rating' => [
                'nullable',
                'integer',
                'min:1',
                'max:5',
            ],

            'description' => ['nullable', 'array'],
            'description.en' => ['nullable', 'string'],
            'description.id' => ['nullable', 'string'],
        ]);

        $ingredient->update([
            'name' => $validated['name'],
            'inci_name' => $validated['inci_name'] ?? null,
            'safety_rating' => $validated['safety_rating'] ?? null,
            'description' => $validated['description'] ?? [],
        ]);

        return redirect()
            ->route('admin.ingredients.index')
            ->with('success', 'Ingredient updated successfully.');
    }
}
