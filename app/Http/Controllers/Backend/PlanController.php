<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Modules;
use App\Models\Plan;
use App\Models\Service;
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
            'admins' => Plan::where('is_deleted', '0')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['plan.create']);
        $modules = Service::where('is_deleted', '0')->where('status', 1)->get();

        return view('backend.pages.plans.create', ['modules' => $modules]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['plan.create']);
        $modules = implode(', ', $request->modules);
        $admin = new Plan();
        $admin->plan_name = $request->plan_name;
        $admin->sort_desc = $request->sort_desc;
        $admin->price = $request->price;
        $admin->price = $request->price;
        $admin->price = $request->price;
        $admin->validity = $request->validity;
        $admin->session = $request->session;
        $admin->description = $request->description;
        $admin->plan_type = $request->plan_type;
        $admin->duration = $request->duration;
        $admin->month_duration = $request->month_duration;
        $admin->personal_meeting = $request->personal_meeting;
        $admin->deliveries = $request->deliveries;
        $admin->duration_year = $request->duration_year;
        $admin->cmd_visit = $request->cmd_visit;
        $admin->store_visit = $request->store_visit;
        $admin->module_ids = $modules;
        $admin->tax = $request->tax;
        $admin->status = $request->status;
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
        $modules = Service::where('status', 1)->get();
        return view('backend.pages.plans.edit', [
            'admin' => $admin,
            'roles' => Role::all(),
            'modules' => $modules,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['plan.edit']);
        $modules = implode(', ', $request->modules);
        $admin = Plan::findOrFail($id);
        $admin->plan_name = $request->plan_name;
        $admin->sort_desc = $request->sort_desc;
        $admin->price = $request->price;
        $admin->validity = $request->validity;
        $admin->session = $request->session;
        $admin->description = $request->description;
        $admin->plan_type = $request->plan_type;
        $admin->duration = $request->duration;
        $admin->month_duration = $request->month_duration;
        $admin->personal_meeting = $request->personal_meeting;
        $admin->deliveries = $request->deliveries;
        $admin->duration_year = $request->duration_year;
        $admin->cmd_visit = $request->cmd_visit;
        $admin->store_visit = $request->store_visit;
        $admin->tax = $request->tax;
        $admin->module_ids = $modules;
        $admin->status = $request->status;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('plans'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', 'Plan has been updated.');
        return redirect()->route('admin.plan.index'); //back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['plan.delete']);

        $admin = Plan::findOrFail($id);
        $admin->is_deleted = '1';
        $admin->save();
        session()->flash('success', 'Plan has been deleted.');
        return back();
    }
}
