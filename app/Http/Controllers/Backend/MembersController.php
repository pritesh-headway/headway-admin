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
use App\Models\Service;
use App\Models\StartupServiceModules;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use App\Services\CurlApiService;
use App\Services\FcmNotificationService;
use Illuminate\Support\Facades\DB;

class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['members.create']);

        return view('backend.pages.members.index', [
            'admins' => Membership::where('is_deleted', '0')->where(['status' => '1', 'membership_status' => 'Approved'])->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['members.create']);

        return view('backend.pages.members.create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['members.create']);

        $admin = new Member();
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

        session()->flash('success', __('Client has been created.'));
        return redirect()->route('admin.members.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['members.edit']);

        $admin = Membership::findOrFail($id);
        $plans = Plan::findOrFail($admin['product_id']);

        $ids = explode(',', $plans->module_ids);
        $modulesName = Service::whereIn('id', $ids)->where('status', 1)->get();
        $trainers = DB::table('trainers')
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->get();

        $subjects =  DB::table('trainer_subjects')
            ->where('status', '1')
            ->where('is_deleted', '0')
            ->get();

        $dataGetNodules = MemberModule::where('member_id', $admin->user_id)->get();
        $dataGetNodulesSubject = MemberModuleSubject::where('member_id', $admin->user_id)->get();
        // dd($plans->page_type);
        if ($plans->page_type == 'mmb') {
            $dataGetNodules = MemberModule::where('member_id', $admin->user_id)->get();
            $pageName = 'backend.pages.members.edit';
        } elseif ($plans->page_type == 'start-up') {
            $dataGetNodules = MemberStartupModule::where('member_id', $admin->user_id)->get();
            $pageName = 'backend.pages.members.editstartup';
        } elseif ($plans->page_type == 'idp') {
            $dataGetNodules = [];
            $pageName = 'backend.pages.members.editidp';
        } elseif ($plans->page_type == 'revision-batch') {
            $dataGetNodules = [];
            $pageName = 'backend.pages.members.editrevision';
        } elseif ($plans->page_type == 'stay-aware-live-renewal') {
            $dataGetNodules = [];
            $pageName = 'backend.pages.members.editstay';
        } elseif ($plans->page_type == 'meeting-with-sir') {
            $dataGetNodules = [];
            $pageName = 'backend.pages.members.editmeeting';
        }

        return view($pageName, [
            'admin' => $admin,
            'plans' => $plans,
            'member_id' => $admin->user_id,
            'membership_id' => $admin->product_id,
            'modulesName' => $modulesName,
            'dataGetNodules' => $dataGetNodules,
            'dataGetNodulesSubject' => $dataGetNodulesSubject,
            'subjects' => $subjects,
            'trainers' => $trainers,
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
        return redirect()->route('admin.members.index'); //back();
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
                    'member_id' => $request->memberID,
                    'module_id' => $request->serviceID,
                    'membership_id' => $request->membershipID,
                    'date' => $request->date,
                    // 'time' => $request->time,
                    // 'remarks' => $request->remarks,
                ],
                [
                    'module_id' => $request->serviceID,
                    'member_id' => $request->memberID,
                    'membership_id' => $request->membershipID,
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

    //Startup Module Data
    public function addUpdateStartupModuleData(Request $request)
    {
        // dd($request);
        try {
            $members = MemberStartupModule::updateOrInsert(
                [
                    'member_id' => $request->memberID,
                    'startup_id' => $request->startupID,
                    'membership_id' => $request->membershipID,
                    'date' => $request->date,
                ],
                [
                    'startup_id' => $request->startupID,
                    'member_id' => $request->memberID,
                    'membership_id' => $request->membershipID,
                    'trainer_id' => $request->trainer_id,
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

    public function addUpdateModuleSubjectData(Request $request)
    {
        // dd($request);
        try {
            $members = MemberModuleSubject::updateOrInsert(
                [
                    'member_id' => $request->memberID,
                    'module_id' => $request->serviceID,
                    'subject_id' => $request->subject_id,
                    'subject_sub_name' => $request->subject_sub_name,
                    'membership_id' => $request->membershipID,
                    'date' => $request->date,
                ],
                [
                    'module_id' => $request->serviceID,
                    'member_id' => $request->memberID,
                    'membership_id' => $request->membershipID,
                    'trainer_id' => $request->trainer_id,
                    'subject_id' => $request->subject_id,
                    'subject_sub_name' => $request->subject_sub_name,
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

    public function addUpdateModuleDataText(Request $request)
    {
        try {
            $members = MemberModule::updateOrInsert(
                [
                    'member_id' => $request->memberID,
                    'module_id' => $request->serviceID,
                    'membership_id' => $request->membershipID,
                    'date' => NULL,
                ],
                [
                    'module_id' => $request->serviceID,
                    'member_id' => $request->memberID,
                    'membership_id' => $request->membershipID,
                    'trainer_id' => $request->trainer_id,
                    'subject_id' => $request->subject_id,
                    'description' => $request->remarks
                ]
            );
            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false]);
        }
    }
}
