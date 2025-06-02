<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\OurCourses;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['ourcourses.create']);

        return view('backend.pages.courses.index', [
            'admins' => OurCourses::where('is_deleted', '0')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['ourcourses.create']);

        return view('backend.pages.courses.create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['ourcourses.create']);

        $admin = new OurCourses();
        $admin->name = $request->name;
        $admin->description = $request->info;
        $admin->video_url = $request->url;
        $admin->status = $request->status;
        $admin->save();

        session()->flash('success', __('Course has been created.'));
        return redirect()->route('admin.courses.index');
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
        $this->checkAuthorization(auth()->user(), ['ourcourses.edit']);

        $admin = OurCourses::findOrFail($id);
        return view('backend.pages.courses.edit', [
            'admin' => $admin,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['ourcourses.edit']);
        $admin = OurCourses::findOrFail($id);
        $admin->name = $request->name;
        $admin->description = $request->info;
        $admin->video_url = $request->url;
        $admin->status = $request->status;
        $admin->save();

        session()->flash('success', 'Course has been updated.');
        return redirect()->route('admin.courses.index'); //back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['ourcourses.delete']);

        $admin = OurCourses::findOrFail($id);
        $admin->is_deleted = '1';
        $admin->save();
        session()->flash('success', 'Course has been deleted.');
        return back();
    }
}
