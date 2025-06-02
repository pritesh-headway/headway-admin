<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class VideoGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['videogallery.create']);
        $plan = Plan::where('is_deleted', '0')->where('status', '1')->pluck('plan_name', 'id')->toArray();
        return view('backend.pages.videos.index', [
            'admins' => Video::where('is_deleted', '0')->get(),
            'plan' => $plan,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['videogallery.create']);
        $plan = Plan::where('is_deleted', '0')->where('status', '1')->get();
        return view('backend.pages.videos.create', ['plan' => $plan]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['videogallery.create']);

        $admin = new Video();
        $admin->name = $request->name;
        $admin->info = $request->info;
        $admin->url = $request->url;
        $admin->type = $request->type;
        $admin->status = $request->status;
        $admin->plan_id = $request->plan_id;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('banners'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', __('Video has been created.'));
        return redirect()->route('admin.videogallery.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Video $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['videogallery.edit']);
        $plan = Plan::where('is_deleted', '0')->where('status', '1')->get();
        $admin = Video::findOrFail($id);
        return view('backend.pages.videos.edit', [
            'admin' => $admin,
            'roles' => Role::all(),
            'plan' => $plan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['videogallery.edit']);
        $admin = Video::findOrFail($id);
        $admin->name = $request->name;
        $admin->info = $request->info;
        $admin->url = $request->url;
        $admin->type = $request->type;
        $admin->status = $request->status;
        $admin->plan_id = $request->plan_id;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('banners'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', 'Video has been updated.');
        return redirect()->route('admin.videogallery.index'); //back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['videogallery.delete']);

        $admin = Video::findOrFail($id);
        $admin->is_deleted = '1';
        $admin->save();
        session()->flash('success', 'Video has been deleted.');
        return back();
    }
}
