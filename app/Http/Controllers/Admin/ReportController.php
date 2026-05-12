<?php

namespace App\Http\Controllers\Admin;

use App\Models\Report;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        $page = 10;
        $sort = 'asc';
        $reports = Report::when($request->q, function($query, $q){
            $query->where('message', 'like', '%'.$q.'%');
        })
        // ->when(request('sort'), function ($q) {
        //     $direction = request('direction', 'asc');
        //     $q->orderBy(request('sort'), $direction);
        // })
        ->with([
            'user',
            'reportable'
        ])
        ->paginate($page)
        ->withQueryString();

    //     $reports = Report::with([
    //     'user',
    //     'reportable'
    // ])->latest()->get();

        return Inertia::render('Admin/Report/Index', [
           'page' => $page,
            'reports' => $reports,
            'search' => $request->only('q'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportable_id'   => 'required',
            'reportable_type' => 'required|string',
            'reason'          => 'required|string|max:100',
            'message'         => 'nullable|string',
        ]);

        Report::create([
            'reportable_id'   => $validated['reportable_id'],
            'reportable_type' => $validated['reportable_type'],
            'reason'          => $validated['reason'],
            'message'         => $validated['message'] ?? null,
            'user_id'         => auth()->id(),
            'guest_token'     => auth()->check() ? null : session()->getId(),
        ]);

        return back()->with('success', 'Report submitted.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $type = Report::findOrFail($id);
        $type->delete();
        return redirect()->back()->with('success', 'Report deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:reports,id'],
        ]);

        Report::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected items deleted.');
    }
}
