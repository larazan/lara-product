<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FaqController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = 10;
        $sort = 'asc';
        // $faqs = Faq::when($request->q, function($query, $q){
        //     $query->where('question', 'like', '%'.$q.'%');
        // })
        // ->when(request('sort'), function ($q) {
        //     $direction = request('direction', 'asc');
        //     $q->orderBy(request('sort'), $direction);
        // })
        //  ->paginate($page)
        // ->withQueryString();
        
        $faqs = Faq::where('is_active', 1)->orderBy('order_position', 'asc')->get();

        return Inertia::render('Admin/Faq/Index', [
           'page' => $page,
            'faqs' => $faqs,
            'search' => $request->only('q'),
            'locales' => config('app.locales'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $question = $request->question;
        $answer = $request->answer;

        Faq::create([
            'question' => $question,
            'answer' => $answer,
        ]);

        return redirect()->route('admin.faq.index')->with('success', 'Faq created successfully.');
    }

    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $faq = Faq::findOrFail($id);

        $faq->question = $request->question;
        $faq->answer = $request->answer;

        $faq->update();
        return redirect()->route('admin.faq.index')->with('success', 'Faq updated successfully.');
    }

    public function destroy(string $id)
    {
        //
        $faq = Faq::findOrFail($id);
        $faq->delete();
        return redirect()->back()->with('success', 'Faq deleted successfully.');
    }

    public function toggle(Faq $faq, Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $faq->update(['is_active' => $request->is_active]);

        return back()->with('success', 'Faq status updated successfully!');
    }

    public function updateOrder(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $index => $id) {
            Faq::where('id', $id)->update(['order_position' => $index + 1]);
        }

        // return back()->with('success', 'FAQ order updated successfully!');
        return response()->json(['success' => true]);
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:faqs,id'],
        ]);

        Faq::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected items deleted.');
    }
}
