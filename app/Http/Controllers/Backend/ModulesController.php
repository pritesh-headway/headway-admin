<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cms;
use App\Models\Modules;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class ModulesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['modules.create']);

        return view('backend.pages.modules.index', [
            'admins' => Modules::where('is_deleted', '0')->orderBy('id', 'DESC')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['admin.create']);
        $services = Service::where('is_deleted', '0')->where('status', 1)->get();
        return view('backend.pages.modules.create', ['services' => $services]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['modules.create']);

        $admin = new Modules();
        $admin->name = $request->name;
        $admin->sort_desc = $request->desc;
        $admin->service_id = $request->service_id;
        $admin->status = $request->status;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('cms'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', __('Module has been created.'));
        return redirect()->route('admin.modules.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cms $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['modules.edit']);

        $admin = Modules::findOrFail($id);
        $services = Service::where('is_deleted', '0')->where('status', 1)->get();
        return view('backend.pages.modules.edit', [
            'admin' => $admin,
            'services' => $services,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['modules.edit']);

        $admin = Modules::findOrFail($id);
        $admin->name = $request->name;
        $admin->sort_desc = $request->desc;
        $admin->service_id = $request->service_id;
        $admin->status = $request->status;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('banners'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', 'Module has been updated.');
        return redirect()->route('admin.modules.index'); //back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['modules.delete']);

        $admin = Modules::findOrFail($id);
        $admin->is_deleted = '1';
        $admin->save();
        session()->flash('success', 'Module has been deleted.');
        return back();
    }
}
