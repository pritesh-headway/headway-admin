<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class StartupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $this->checkAuthorization(auth()->user(), ['aboutstartup.view']);

        $startups = DB::table('about_startups')->get();

        return view('backend.pages.startups.index', compact('startups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $this->checkAuthorization(auth()->user(), ['aboutstartup.create']);

        return view('backend.pages.startups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // $this->checkAuthorization(auth()->user(), ['aboutstartup.create']);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable',
        ]);

        DB::table('about_startups')->insert([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ? 1 : 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        session()->flash('success', __('Startup info has been created.'));
        return redirect()->route('admin.startups.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        // $this->checkAuthorization(auth()->user(), ['aboutstartup.edit']);

        $startup = DB::table('about_startups')->where('id', $id)->first();

        return view('backend.pages.startups.edit', compact('startup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        // $this->checkAuthorization(auth()->user(), ['aboutstartup.edit']);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable',
        ]);

        DB::table('about_startups')->where('id', $id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ? 1 : 0,
            'updated_at' => now(),
        ]);

        session()->flash('success', __('Startup info has been updated.'));
        return redirect()->route('admin.startups.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        // $this->checkAuthorization(auth()->user(), ['aboutstartup.delete']);

        DB::table('about_startups')->where('id', $id)->delete();

        session()->flash('success', __('Startup info has been deleted.'));
        return back();
    }
}
