<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['plan.create']);

        return view('backend.pages.plans.index', [
            'admins' => Plan::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['plan.create']);

        return view('backend.pages.plans.create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['plan.create']);

        $admin = new Plan();
        $admin->plan_name = $request->plan_name;
        $admin->sort_desc = $request->sort_desc;
        $admin->price = $request->price;
        $admin->validity = $request->validity;
        $admin->session = $request->session;
        $admin->description = $request->description;
        $admin->plan_type = $request->plan_type;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('plans'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', __('Plan has been created.'));
        return redirect()->route('admin.plan.index');
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
        $this->checkAuthorization(auth()->user(), ['plan.edit']);

        $admin = Plan::findOrFail($id);
        return view('backend.pages.plans.edit', [
            'admin' => $admin,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['plan.edit']);

        $admin = Plan::findOrFail($id);
        $admin->plan_name = $request->plan_name;
        $admin->sort_desc = $request->sort_desc;
        $admin->price = $request->price;
        $admin->validity = $request->validity;
        $admin->session = $request->session;
        $admin->description = $request->description;
        $admin->plan_type = $request->plan_type;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('plans'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', 'Plan has been updated.');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['plan.delete']);

        $admin = Plan::findOrFail($id);
        $admin->delete();
        session()->flash('success', 'Plan has been deleted.');
        return back();
    }
}
