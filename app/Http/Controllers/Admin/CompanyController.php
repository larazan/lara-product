<?php

namespace App\Http\Controllers\Admin;

use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CompanyController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = 10;
        $sort = 'asc';
        $companies = Company::when($request->q, function ($query, $q) {
            $query->where('name', 'like', '%' . $q . '%');
        })
            // ->when(request('sort'), function ($q) {
            //     $direction = request('direction', 'asc');
            //     $q->orderBy(request('sort'), $direction);
            // })
            ->paginate($page)
            ->withQueryString();

        $countries = Country::orderBy('name', 'desc')->get();

        return Inertia::render('Admin/Company/Index', [
            'page' => $page,
            'companies' => $companies,
            'countries' => $countries,
            'search' => $request->only('q'),
            'locales' => config('app.locales'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|array',
        ]);

        $company = new Company();
        $company->country_id = $request->countryId;
        $company->name = $request->name;
        $company->description = $request->description;
        $company->legal_name = $request->legalName;
        $company->website = $request->website;
        $company->email = $request->email;

        // upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            // $image->move('image', $uniqueName);

            // WebP
            // Initialize Intervention Image Manager with the desired driver
            // GD is usually a safe bet, ensure it's installed on your server
            $manager = new ImageManager(new Driver());

            // Read the uploaded image from its temporary path
            $img = $manager->read($image->getRealPath());

            // Generate a unique filename for the WebP image
            $fileName = $uniqueName . '.webp';
            // Encode the image to WebP format with desired quality (e.g., 80%)
            $encodedImage = $img->toWebp(quality: 80);

            // Define the storage path (e.g., 'public/uploads')
            $filePath = 'uploads/companies/' . $fileName;

            // Store the WebP image using Laravel's Storage facade
            Storage::disk('public')->put($filePath, $encodedImage);

            $company->logo = $filePath;
        }

        $company->save();

        return redirect()->route('admin.company.index')->with('success', 'Company created successfully.');
    }

    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required|array',
        ]);

        $company = Company::findOrFail($id);

        $company->country_id = $request->countryId;
        $company->name = $request->name;
        $company->description = $request->description;
        $company->legal_name = $request->legalName;
        $company->website = $request->website;
        $company->email = $request->email;

        // upload image
        if ($request->hasFile('image')) {
            // delete image
            $this->deleteImage($id);

            $image = $request->file('image');
            $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            // $image->move('image', $uniqueName);

            // WebP
            // Initialize Intervention Image Manager with the desired driver
            // GD is usually a safe bet, ensure it's installed on your server
            $manager = new ImageManager(new Driver());

            // Read the uploaded image from its temporary path
            $img = $manager->read($image->getRealPath());

            // Generate a unique filename for the WebP image
            $fileName = $uniqueName . '.webp';
            // Encode the image to WebP format with desired quality (e.g., 80%)
            $encodedImage = $img->toWebp(quality: 80);

            // Define the storage path (e.g., 'public/uploads')
            $filePath = 'uploads/categories/' . $fileName;

            // Store the WebP image using Laravel's Storage facade
            Storage::disk('public')->put($filePath, $encodedImage);

            $company->logo = $filePath;
        }

        $company->update();
        return redirect()->route('admin.company.index')->with('success', 'Company updated successfully.');
    }

    public function deleteImage($id = null)
    {
        $image = Company::findOrFail($id);
        $path = 'storage/';

        // ORIGINAL 
        if ($image->logo || File::exists(public_path($path . $image->logo))) {
            File::delete(public_path($path . $image->logo));
        }

        return true;
    }

    public function destroy(string $id)
    {
        //
        $company = Company::findOrFail($id);
        $company->delete();
        return redirect()->back()->with('success', 'Company deleted successfully.');
    }

    public function toggle(Company $company, Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $company->update(['is_active' => $request->is_active]);

        return back()->with('success', 'Company status updated successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:companies,id'],
        ]);

        Company::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected items deleted.');
    }
}
