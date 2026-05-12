<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    //
    public function index()
    {
        $settings = Setting::all()
            ->mapWithKeys(fn($item) => [$item->key => $item->value]);

        return Inertia::render('Admin/Setting/Index', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        foreach ($request->all() as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
