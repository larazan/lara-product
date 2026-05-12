<?php

namespace App\Http\Controllers\Admin;

use App\Models\Advertising;
use App\Models\AdvertisingSegment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AdvertisingController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = 10;
        $sort = 'asc';
        $adverts = Advertising::when($request->q, function($query, $q){
            $query->where('title', 'like', '%'.$q.'%');
        })
        ->with('segment')
        // ->when(request('sort'), function ($q) {
        //     $direction = request('direction', 'asc');
        //     $q->orderBy(request('sort'), $direction);
        // })
         ->paginate($page)
        ->withQueryString();

        $segments = AdvertisingSegment::orderBy('id')->get();

        return Inertia::render('Admin/Advertising/Index', [
           'page' => $page,
            'adverts' => $adverts,
            'segments' => $segments,
            'search' => $request->only('q'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $advert = new Advertising();
        $advert->title = $request->title;
        $advert->segment_id = $request->segmentId;
        $advert->description = $request->description;
        $advert->start = $request->start;
        $advert->end = $request->end;
        $advert->url = $request->url;

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
            $filePath = 'uploads/advertisings/' . $fileName;

            // Store the WebP image using Laravel's Storage facade
            Storage::disk('public')->put($filePath, $encodedImage);

            $advert->original = $filePath;
        }

        $advert->save();

        return redirect()->route('admin.advertising.index')->with('success', 'Advertising created successfully.');
    }

    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'title' => 'required',
        ]);

        $advert = Advertising::findOrFail($id);

        $advert->title = $request->title;
        $advert->segment_id = $request->segmentId;
        $advert->description = $request->description;
        $advert->start = $request->start;
        $advert->end = $request->end;
        $advert->url = $request->url;

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
            $filePath = 'uploads/advertisings/' . $fileName;

            // Store the WebP image using Laravel's Storage facade
            Storage::disk('public')->put($filePath, $encodedImage);

            $advert->original = $filePath;
        }

        $advert->update();
        return redirect()->route('admin.advertising.index')->with('success', 'Advertising updated successfully.');
    }

    public function deleteImage($id = null) {
        $image = Advertising::findOrFail($id);
		$path = 'storage/';

        // ORIGINAL 
        if ($image->image || File::exists(public_path($path.$image->image))) {
            File::delete(public_path($path.$image->image));
		}
             
        return true;
    }

    public function destroy(string $id)
    {
        //
        $advert = Advertising::findOrFail($id);
        $advert->delete();
        return redirect()->back()->with('success', 'Advertising deleted successfully.');
    }
    
    public function toggle(Advertising $advert, Request $request)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $advert->update(['is_active' => $request->is_active]);

        return back()->with('success', 'Advertising status updated successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:categories,id'],
        ]);

        Advertising::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected items deleted.');
    }
}
