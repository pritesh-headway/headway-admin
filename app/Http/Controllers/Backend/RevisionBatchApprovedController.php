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

class RevisionBatchApprovedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['members.create']);

        return view('backend.pages.revision-batch-approved.index', [
            'admins' => RevisionBatch::where(['status' => '1', 'revison_batch_status' => 'Approved'])->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['members.create']);

        return view('backend.pages.revision-batch-approved.create', []);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['revisionbatch.edit']);

        $admin = RevisionBatch::findOrFail($id);
        $plans = Plan::findOrFail(11);
        // dd($plans);
        $ids = explode(',', $plans->module_ids);
        $modulesName = Modules::whereIn('id', $ids)->where('status', 1)->get();
        // dd($modulesName);
        // $modulesName = Modules::where('status', 1)->where('plan_type', 'Revision')->get();
        $pageName = 'backend.pages.revision-batch-approved.edit';
        $dataGetNodules = MemberModule::where('member_id', $admin->user_id)->where('membership_id', 11)->get();
        return view($pageName, [
            'admin' => $admin,
            'member_id' => $admin->user_id,
            'modulesName' => $modulesName,
            'dataGetNodules' => $dataGetNodules,
            'roles' => Role::all(),
        ]);
    }

    public function getSubjectData(Request $request)
    {
        $subject_id = $request->input('subject_id');
        $member_id = $request->input('member_id');
        $module_id = $request->input('module_id');
        $membership_id = $request->input('membership_id');

        $visitData = MemberModuleSubject::where(['subject_id' => $subject_id, 'membership_id' => $membership_id, 'member_id' => $member_id, 'module_id' => $module_id])->get();
        // dd($visitData);
        $trainers = DB::table('trainers')
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->get();

        return response()->json([
            'data'     => $visitData,
            'trainers' => $trainers,
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
        return redirect()->route('admin.revision-batch-approved.index'); //back();
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
