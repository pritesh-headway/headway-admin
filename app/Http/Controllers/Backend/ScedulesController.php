<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Batch;
use App\Models\Modules;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class ScedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['scedule.create']);

        return view('backend.pages.scedules.index', [
            'admins' => Banner::where('is_deleted', '0')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['scedule.create']);
        $modules = Modules::where('status', '1')->get();
        $clients = User::where('is_deleted', '0')->where('status', '1')->get();
        $batches = Batch::where('status', '1')->get();
        return view('backend.pages.scedules.create', ['modules' => $modules, 'clients' => $clients, 'batches' => $batches]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        dd($request);
        $this->checkAuthorization(auth()->user(), ['scedule.create']);

        $admin = new Banner();
        $admin->title = $request->title;
        $admin->heading = $request->heading;
        $admin->is_popup = isset($request->is_popup) && $request->is_popup == 'on' ? 1 : 0;
        $admin->desc = $request->desc;
        $admin->status = $request->status;

        $admin->save();

        session()->flash('success', __('Scedule has been created.'));
        return redirect()->route('admin.scedules.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['scedule.edit']);

        $admin = Banner::findOrFail($id);
        return view('backend.pages.scedules.edit', [
            'admin' => $admin,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['scedule.edit']);
        $admin = Banner::findOrFail($id);
        $admin->title = $request->title;
        $admin->heading = $request->heading;
        $admin->is_popup = isset($request->is_popup) && $request->is_popup == 'on' ? 1 : 0;
        $admin->desc = $request->desc;
        $admin->status = $request->status;

        $admin->save();

        session()->flash('success', 'Scedule has been updated.');
        return redirect()->route('admin.scedules.index'); //back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['scedule.delete']);

        $admin = Banner::findOrFail($id);
        $admin->is_deleted = '1';
        $admin->save();
        session()->flash('success', 'Scedule has been deleted.');
        return back();
    }
}
