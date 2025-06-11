<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return view('backend.pages.settings.index', compact('settings'));
    }

    public function create()
    {
        return view('backend.pages.settings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|in:0,1',
            'type' => 'required|in:text,file',
            'group' => 'nullable|string',
            'desc' => 'nullable|string',
            'value' => 'required_if:type,text|nullable',
            'file' => 'required_if:type,file|file'
        ]);

        $data = $request->only(['name', 'status', 'type', 'group', 'desc']);

        if ($request->type === 'file' && $request->hasFile('file')) {
            // Save the file in public/settings/
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('settings'), $fileName);
            $data['value'] = 'settings/' . $fileName;
        } else {
            $data['value'] = $request->value;
        }

        Setting::create($data);

        return redirect()->route('admin.settings.index')->with('success', 'Setting created successfully.');
    }


    public function edit($id)
    {
        $setting = Setting::findOrFail($id);
        return view('backend.pages.settings.edit', compact('setting'));
    }

    public function update(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'status' => 'required|in:0,1',
            'type' => 'required|in:text,file',
            'group' => 'nullable|string',
            'desc' => 'nullable|string',
        ]);

        $data = $request->only(['name', 'status', 'type', 'group', 'desc']);

        if ($request->type === 'text') {
            $request->validate([
                'value' => 'required|string',
            ]);
            $data['value'] = $request->value;
        } elseif ($request->type === 'file') {
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Get original name and sanitize the base name (without extension)
                $originalName = $file->getClientOriginalName();
                $sanitizedName = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));

                // Get the correct file extension
                $extension = $file->getClientOriginalExtension();

                // Final filename with sanitized name + extension
                $filename = time() . '_' . $sanitizedName . '.' . $extension;
                // Move file
                $file->move(public_path('settings'), $filename);

                // Save to DB
                $data['value'] = 'settings/' . $filename;

            } else {
                // Keep existing file path if no new file is uploaded
                $data['value'] = $setting->value;
            }
        }

        $setting->update($data);

        return redirect()->route('admin.settings.index')->with('success', 'Setting updated successfully.');
    }


    public function destroy($id)
    {
        $setting = Setting::findOrFail($id);
        $setting->delete();

        return redirect()->route('admin.settings.index')->with('success', 'Setting deleted successfully.');
    }
}
