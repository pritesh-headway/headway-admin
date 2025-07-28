<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Client;
use App\Models\Member;
use App\Models\MemberModule;
use App\Models\MemberModuleSubject;
use App\Models\Membership;
use App\Models\MemberStartupModule;
use App\Models\Modules;
use App\Models\Plan;
use App\Models\RevisionBatch;
use App\Models\Service;
use App\Models\StartupServiceModules;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use App\Services\CurlApiService;
use App\Services\FcmNotificationService;
use Google\Service\CloudTrace\Module;
use Illuminate\Support\Facades\DB;

class StayawareBatchApprovedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['members.create']);

        return view('backend.pages.stay-aware-approved.index', [
            'admins' => RevisionBatch::where(['status' => '1', 'revison_batch_status' => 'Approved', 'form_type' => 'Stay_Aware'])->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['members.create']);

        return view('backend.pages.stay-aware-approved.create', []);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['revisionbatch.edit']);

        $admin = RevisionBatch::findOrFail($id);
        $plans = Plan::findOrFail($admin['plan_id']);

        $ids = explode(',', $plans->module_ids);
        $modulesName = Service::whereIn('id', $ids)->where('status', 1)->get();
        // $modulesName = Service::where('status', 1)->where('plan_type', 'Stay Aware')->get();
        // dd($modulesName);
        $pageName = 'backend.pages.stay-aware-approved.edit';
        $dataGetNodules = MemberModule::where('member_id', $admin->user_id)->where('membership_id', $admin['plan_id'])->get();
        return view($pageName, [
            'admin' => $admin,
            'member_id' => $admin->user_id,
            'modulesName' => $modulesName,
            'dataGetNodules' => $dataGetNodules,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['members.edit']);

        $admin = Member::findOrFail($id);
        $admin->name = $request->name;
        $admin->city = $request->city;
        $admin->description = $request->description;
        $admin->status = $request->status;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('clients'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', 'Client has been updated.');
        return redirect()->route('admin.stay-aware-approved.index'); //back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['members.delete']);

        $admin = Member::findOrFail($id);
        $admin->is_deleted = '1';
        $admin->save();
        session()->flash('success', 'Client has been deleted.');
        return back();
    }

    public function addUpdateModuleData(Request $request)
    {
        // dd($request);
        try {
            $members = MemberModule::updateOrInsert(
                [
                    'member_id' => $request->member_id,
                    'module_id' => $request->module_id,
                    'membership_id' => $request->membership_id,
                    'date' => $request->date,
                    // 'time' => $request->time,
                    // 'remarks' => $request->remarks,
                ],
                [
                    'module_id' => $request->module_id,
                    'member_id' => $request->member_id,
                    'membership_id' => $request->membership_id,
                    'trainer_id' => $request->trainer_id,
                    // 'subject_id' => $request->subject_id,
                    'date' => $request->date,
                    'time' => $request->time,
                    'module_status' => $request->status,
                    'remarks' => $request->remarks
                ]
            );
            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false]);
        }
    }
}
