<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    private function resolveTable(string $type): string
    {
        return match ($type) {
            'ssu' => 'ssu_galleries',
            'mmb' => 'mmb_galleries',
            default => abort(404, 'Invalid gallery type'),
        };
    }

    private function resolveFolder(string $type): string
    {
        return match ($type) {
            'ssu' => 'ssu_gallery',
            'mmb' => 'mmb_gallery',
            default => abort(404, 'Invalid gallery type'),
        };
    }

    public function index()
    {
        $ssuGalleries = DB::table('ssu_galleries')->get();
        $mmbGalleries = DB::table('mmb_galleries')->get();
        return view('backend.pages.gallery.index', compact('ssuGalleries', 'mmbGalleries'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type');
        $this->resolveTable($type); // Ensures valid type
        return view('backend.pages.gallery.create', compact('type'));
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'images' => 'required|file'
    ]);

    $type = $request->get('type');
    $table = $this->resolveTable($type);
    $folder = $this->resolveFolder($type);

    $fileName = null;

    if ($request->hasFile('images')) {
        $file = $request->file('images');
        $fileName = time() . '.' . $file->getClientOriginalExtension(); // e.g., 1712905289.webp
        $file->move(public_path($folder), $fileName);
    }

    DB::table($table)->insert([
        'title' => $request->title,
        'images' => $fileName, // plain string
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.gallery.index')->with('success', 'Gallery created successfully.');
}


    public function edit(Request $request, $id)
    {
        $type = $request->get('type');
        $table = $this->resolveTable($type);

        $gallery = DB::table($table)->where('id', $id)->first();
        if (!$gallery) abort(404);

        return view('backend.pages.gallery.edit', compact('gallery', 'type'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'images' => 'nullable|file'
    ]);

    $type = $request->get('type');
    $table = $this->resolveTable($type);
    $folder = $this->resolveFolder($type);

    $gallery = DB::table($table)->where('id', $id)->first();
    if (!$gallery) abort(404);

    $fileName = $gallery->images;

    if ($request->hasFile('images')) {
        // Delete old image if exists
        if ($fileName && File::exists(public_path("$folder/$fileName"))) {
            File::delete(public_path("$folder/$fileName"));
        }

        $file = $request->file('images');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($folder), $fileName);
    }

    DB::table($table)->where('id', $id)->update([
        'title' => $request->title,
        'images' => $fileName, // plain string
        'updated_at' => now(),
    ]);

    return redirect()->route('admin.gallery.index')->with('success', 'Gallery updated successfully.');
}


    public function destroy($id, $type)
{
    // $this->checkAuthorization(auth()->user(), ['gallery.delete']);

    $table = $this->resolveTable($type);
    $folder = $this->resolveFolder($type);

    $gallery = DB::table($table)->where('id', $id)->first();
    if (!$gallery) {
        abort(404);
    }

    // Delete image file from disk
    $image = $gallery->images; // single image filename (e.g., 1712905289.webp)
    $path = public_path($folder . '/' . $image);
    if ($image && File::exists($path)) {
        File::delete($path);
    }

    // Delete from database
    DB::table($table)->where('id', $id)->delete();

    return redirect()->back()->with('success', 'Gallery deleted successfully.');
}

}
