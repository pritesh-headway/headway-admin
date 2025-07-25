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
    public function create(Request $request)
    {
        // dd($request->type);
        $this->checkAuthorization(auth()->user(), ['plan.create']);
        $modules = Service::where('is_deleted', '0')->where('status', 1)->get();
        if ($request->type == 'mmb') {
            return view('backend.pages.plans.create', ['modules' => $modules, 'page_type' => $request->type]);
        } elseif ($request->type == 'start-up') {
            return view('backend.pages.plans.startup', ['modules' => $modules, 'page_type' => $request->type]);
        } elseif ($request->type == 'idp') {
            $modules = Service::where('is_deleted', '0')->where('plan_type', 'IDP')->where('status', 1)->get();
            return view('backend.pages.plans.idp', ['modules' => $modules, 'page_type' => $request->type]);
        } elseif ($request->type == 'revision-batch') {
            return view('backend.pages.plans.revision', ['modules' => $modules, 'page_type' => $request->type]);
        } elseif ($request->type == 'stay-aware-live-renewal') {
            $modules = Service::where('is_deleted', '0')->where('plan_type', 'Stay Aware')->where('status', 1)->get();
            return view('backend.pages.plans.stay-aware', ['modules' => $modules, 'page_type' => $request->type]);
        } elseif ($request->type == 'meeting-with-sir') {
            return view('backend.pages.plans.single-meeting', ['modules' => $modules, 'page_type' => $request->type]);
        }
        return view('backend.pages.plans.create', ['modules' => $modules]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        dd($request);
        $this->checkAuthorization(auth()->user(), ['plan.create']);
        $admin = new Plan();
        $admin->plan_name = $request->plan_name;
        $admin->price = $request->price;
        if ($request->page_type == 'mmb') {
            $modules = implode(', ', $request->modules);
            $admin->validity = $request->validity;
            $admin->session = $request->session;
            $admin->duration = $request->duration;
            $admin->personal_meeting = $request->personal_meeting;
            $admin->deliveries = $request->deliveries;
            $admin->duration_year = $request->duration_year;
            $admin->cmd_visit = $request->cmd_visit;
            $admin->store_visit = $request->store_visit;
            $admin->tax = $request->tax;
            $admin->on_call_support = $request->on_call_support;
            $admin->personalization = $request->personalization;
            $admin->documents = $request->documents;
            $admin->module_ids = $modules;
            $admin->price_within_india = $request->price_within_india;
            $admin->price_within_gujrat = $request->price_within_gujrat;
            $admin->month_duration = $request->month_duration;
            $admin->plan_type = $request->plan_type;
        } elseif ($request->page_type == 'start-up') {
            $admin->sqrt = $request->sqrt;
            $admin->employees = $request->employees;
            $admin->stock = $request->stock;
            $admin->tax = $request->tax;
            $admin->price_within_india = $request->price_within_india;
            $admin->price_within_gujrat = $request->price_within_gujrat;
            $admin->plan_type = $request->plan_type;
        } elseif ($request->page_type == 'idp') {
            $modules = implode(', ', $request->modules);
            $admin->validity = $request->validity;
            $admin->session = $request->session;
            $admin->duration = $request->duration;
            $admin->personal_meeting = $request->personal_meeting;
            $admin->deliveries = $request->deliveries;
            $admin->duration_year = $request->duration_year;
            $admin->cmd_visit = $request->cmd_visit;
            $admin->store_visit = $request->store_visit;
            $admin->tax = $request->tax;
            $admin->on_call_support = $request->on_call_support;
            $admin->personalization = $request->personalization;
            $admin->documents = $request->documents;
            $admin->module_ids = $modules;
            $admin->price_within_india = $request->price_within_india;
            $admin->price_within_gujrat = $request->price_within_gujrat;
            $admin->month_duration = $request->month_duration;
            $admin->plan_type = $request->plan_type;
        } elseif ($request->page_type == 'revision-batch') {
            $modules = implode(', ', $request->modules);
            $admin->module_ids = $modules;
            $admin->tax = $request->tax;
        } elseif ($request->page_type == 'stay-aware-live-renewal') {
            $modules = implode(', ', $request->modules);
            $admin->personal_meeting = $request->personal_meeting;
            $admin->tax = $request->tax;
            $admin->on_call_support = $request->on_call_support;
            $admin->marketing_campaign_plan_startegy = $request->marketing_campaign_plan_startegy;
            $admin->module_ids = $modules;
        } elseif ($request->page_type == 'meeting-with-sir') {
        }
        $admin->page_type = $request->page_type;
        $admin->description = $request->description;
        $admin->sort_desc = $request->sort_desc;
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
    public function edit(int $id, string $type): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['plan.edit']);

        $admin = Plan::findOrFail($id);
        $modules = Service::where('status', 1)->get();
        if ($type == 'mmb') {
            return view('backend.pages.plans.edit', [
                'admin' => $admin,
                'roles' => Role::all(),
                'modules' => $modules,
                'page_type' => $type,
            ]);
        } elseif ($type == 'start-up') {
            return view('backend.pages.plans.startupedit', [
                'admin' => $admin,
                'roles' => Role::all(),
                'modules' => $modules,
                'page_type' => $type,
            ]);
        } elseif ($type == 'idp') {
            $modules = Service::where('is_deleted', '0')->where('plan_type', 'IDP')->where('status', 1)->get();
            return view('backend.pages.plans.idp-edit', [
                'admin' => $admin,
                'roles' => Role::all(),
                'modules' => $modules,
                'page_type' => $type,
            ]);
        } elseif ($type == 'revision-batch') {
            $modules = Modules::where('status', 1)->get();
            return view('backend.pages.plans.revision-edit', [
                'admin' => $admin,
                'roles' => Role::all(),
                'modules' => $modules,
                'page_type' => $type,
            ]);
        } elseif ($type == 'stay-aware-live-renewal') {
            $modules = Service::where('is_deleted', '0')->where('plan_type', 'Stay Aware')->where('status', 1)->get();
            return view('backend.pages.plans.stay-aware-edit', [
                'admin' => $admin,
                'roles' => Role::all(),
                'modules' => $modules,
                'page_type' => $type,
            ]);
        } elseif ($type == 'meeting-with-sir') {
            return view('backend.pages.plans.single-meeting', [
                'admin' => $admin,
                'roles' => Role::all(),
                'modules' => $modules,
                'page_type' => $type,
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['plan.edit']);

        $admin = Plan::findOrFail($id);
        $admin->plan_name = $request->plan_name;
        $admin->price = $request->price;

        if ($request->page_type == 'mmb') {
            $modules = implode(', ', $request->modules);
            $admin->validity = $request->validity;
            $admin->session = $request->session;
            $admin->duration = $request->duration;
            $admin->personal_meeting = $request->personal_meeting;
            $admin->deliveries = $request->deliveries;
            $admin->duration_year = $request->duration_year;
            $admin->cmd_visit = $request->cmd_visit;
            $admin->store_visit = $request->store_visit;
            $admin->tax = $request->tax;
            $admin->on_call_support = $request->on_call_support;
            $admin->personalization = $request->personalization;
            $admin->documents = $request->documents;
            $admin->module_ids = $modules;
            $admin->price_within_india = $request->price_within_india;
            $admin->price_within_gujrat = $request->price_within_gujrat;
            $admin->month_duration = $request->month_duration;
            $admin->plan_type = $request->plan_type;
        } elseif ($request->page_type == 'start-up') {
            $admin->sqrt = $request->sqrt;
            $admin->employees = $request->employees;
            $admin->tax = $request->tax;
            $admin->stock = $request->stock;
            $admin->plan_type = $request->plan_type;
            $admin->price_within_india = $request->price_within_india;
            $admin->price_within_gujrat = $request->price_within_gujrat;
        } elseif ($request->page_type == 'idp') {
            $modules = implode(', ', $request->modules);
            $admin->validity = $request->validity;
            $admin->session = $request->session;
            $admin->duration = $request->duration;
            $admin->personal_meeting = $request->personal_meeting;
            $admin->deliveries = $request->deliveries;
            $admin->duration_year = $request->duration_year;
            $admin->cmd_visit = $request->cmd_visit;
            $admin->store_visit = $request->store_visit;
            $admin->tax = $request->tax;
            $admin->on_call_support = $request->on_call_support;
            $admin->personalization = $request->personalization;
            $admin->documents = $request->documents;
            $admin->module_ids = $modules;
            $admin->price_within_india = $request->price_within_india;
            $admin->price_within_gujrat = $request->price_within_gujrat;
            $admin->month_duration = $request->month_duration;
            $admin->plan_type = $request->plan_type;
        } elseif ($request->page_type == 'revision-batch') {
            $modules = implode(', ', $request->modules);
            $admin->module_ids = $modules;
            $admin->tax = $request->tax;
        } elseif ($request->page_type == 'stay-aware-live-renewal') {
            $modules = implode(', ', $request->modules);
            $admin->personal_meeting = $request->personal_meeting;
            $admin->tax = $request->tax;
            $admin->on_call_support = $request->on_call_support;
            $admin->marketing_campaign_plan_startegy = $request->marketing_campaign_plan_startegy;
            $admin->module_ids = $modules;
            $admin->price_within_india = $request->price_within_india;
            $admin->price_within_gujrat = $request->price_within_gujrat;
        } elseif ($request->page_type == 'meeting-with-sir') {
        }

        $admin->page_type = $request->page_type;
        $admin->sort_desc = $request->sort_desc;
        $admin->description = $request->description;
        $admin->status = $request->status;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('plans'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        // dd($admin);
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
