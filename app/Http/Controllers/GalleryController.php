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
            'oss' => 'oss_galleries',
            'gen' => 'gen_galleries',
            default => abort(404, 'Invalid gallery type'),
        };
    }

    private function resolveFolder(string $type): string
    {
        return match ($type) {
            'ssu' => 'ssu_gallery',
            'mmb' => 'mmb_gallery',
            'oss' => 'oss_gallery',
            'gen' => 'gen_gallery',
            default => abort(404, 'Invalid gallery type'),
        };
    }

    public function index()
    {
        $ssuGalleries = DB::table('ssu_galleries')->get();
        $mmbGalleries = DB::table('mmb_galleries')->get();
        $ossGalleries = DB::table('oss_galleries')->get();
        $genGalleries = DB::table('gen_galleries')->get();
        return view('backend.pages.gallery.index', compact('ssuGalleries', 'mmbGalleries', 'ossGalleries', 'genGalleries'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type');
        $this->resolveTable($type); // Ensures valid type
        return view('backend.pages.gallery.create', compact('type'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $type = $request->get('type');
        $table = $this->resolveTable($type);
        $folder = $this->resolveFolder($type);

        // Conditional validation
        $rules = [
            'title' => 'required|string|max:255',
        ];

        if ($type === 'gen') {
            $rules['imagesInput'] = 'required|image|mimes:jpeg,png,jpg,webp|max:2048';
        } else {
            $rules['cropped_image'] = 'required|string'; // base64 string
        }

        $validated = $request->validate($rules);

        $fileName = null;

        if ($type === 'gen') {
            // Handle regular uploaded file
            if ($request->hasFile('imagesInput')) {
                $image = $request->file('imagesInput');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $path = public_path($folder);

                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                $image->move($path, $fileName);
            }
        } else {
            // Handle cropped base64 image
            $base64Image = $request->cropped_image;

            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $typeMatch)) {
                $imageType = strtolower($typeMatch[1]);
                $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
                $base64Image = base64_decode($base64Image);

                if ($base64Image === false) {
                    return back()->with('error', 'Invalid image data.');
                }

                $fileName = time() . '.' . $imageType;
                $path = public_path($folder);

                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                file_put_contents($path . '/' . $fileName, $base64Image);
            } else {
                return back()->with('error', 'Invalid image format.');
            }
        }

        // Insert into the respective table
        DB::table($table)->insert([
            'title' => $validated['title'],
            'images' => $fileName,
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
        if (!$gallery)
            abort(404);

        return view('backend.pages.gallery.edit', compact('gallery', 'type'));
    }


    public function update(Request $request, $id)
    {
        $type = $request->get('type');
        $table = $this->resolveTable($type);
        $folder = $this->resolveFolder($type);

        // Validate inputs based on type
        $rules = [
            'title' => 'required|string|max:255',
        ];

        if ($type === 'gen') {
            $rules['imagesInput'] = 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072';
        } else {
            $rules['cropped_image'] = 'nullable|string';
            $rules['imagesInput'] = 'nullable|file|mimes:jpeg,png,jpg,webp|max:3072';
        }

        $validated = $request->validate($rules);

        // Fetch gallery record
        $gallery = DB::table($table)->where('id', $id)->first();
        if (!$gallery) {
            abort(404);
        }

        $fileName = $gallery->images;

        // 🔹 Case: gen (simple file upload, no crop)
        if ($type === 'gen' && $request->hasFile('imagesInput')) {
            $this->deleteOldGalleryImage($folder, $fileName);

            $image = $request->file('imagesInput');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($folder), $fileName);
        }

        // 🔹 Case: cropped image (non-gen types)
        elseif ($type !== 'gen' && $request->filled('cropped_image')) {
            if (preg_match('/^data:image\/(\w+);base64,/', $request->cropped_image, $typeMatch)) {
                $imageType = strtolower($typeMatch[1]);
                $base64Image = substr($request->cropped_image, strpos($request->cropped_image, ',') + 1);
                $base64Image = base64_decode($base64Image);

                if ($base64Image !== false) {
                    $this->deleteOldGalleryImage($folder, $fileName);

                    $fileName = time() . '.' . $imageType;
                    $path = public_path($folder);
                    if (!file_exists($path)) {
                        mkdir($path, 0755, true);
                    }

                    file_put_contents($path . '/' . $fileName, $base64Image);
                }
            }
        }

        // 🔹 Fallback: direct file upload for non-gen types
        elseif ($type !== 'gen' && $request->hasFile('imagesInput')) {
            $this->deleteOldGalleryImage($folder, $fileName);

            $file = $request->file('imagesInput');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path($folder), $fileName);
        }

        // Update the DB
        DB::table($table)->where('id', $id)->update([
            'title' => $validated['title'],
            'images' => $fileName,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery updated successfully.');
    }

    // ✅ Reusable image deletion helper
    protected function deleteOldGalleryImage($folder, $fileName)
    {
        $path = public_path("$folder/$fileName");
        if ($fileName && File::exists($path)) {
            File::delete($path);
        }
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