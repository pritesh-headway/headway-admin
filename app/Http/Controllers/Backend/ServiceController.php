<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Plan;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['service.create']);

        return view('backend.pages.services.index', [
            'admins' => Services::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['service.create']);
        $Services = Services::where('status', '1')->where('parent_id', 0)->get();
        return view('backend.pages.services.create', ['Services' => $Services]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['service.create']);

        $admin = new Services();
        $admin->name = $request->plan_name;
        $admin->sort_desc = $request->sort_desc;
        $admin->service_desc = $request->service_desc;
        $admin->parent_id = $request->parent_id;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('services'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', __('Service has been created.'));
        return redirect()->route('admin.service.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Services $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['service.edit']);

        $admin = Services::findOrFail($id);
        $Services = Services::where('status', '1')->where('parent_id', 0)->get();
        return view('backend.pages.services.edit', [
            'admin' => $admin,
            'Services' => $Services,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['service.edit']);

        $admin = Services::findOrFail($id);
        $admin->name = $request->plan_name;
        $admin->sort_desc = $request->sort_desc;
        $admin->service_desc = $request->service_desc;
        $admin->parent_id = $request->parent_id;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('services'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', 'Service has been updated.');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['service.delete']);

        $admin = Services::findOrFail($id);
        $admin->delete();
        session()->flash('success', 'Services has been deleted.');
        return back();
    }
}
