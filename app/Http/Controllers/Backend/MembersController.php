<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Client;
use App\Models\Member;
use App\Models\MemberModule;
use App\Models\Membership;
use App\Models\Modules;
use App\Models\Plan;
use App\Models\Service;
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
        // dd($admin);
        $dataGetNodules = MemberModule::where('member_id', $admin->user_id)->get();
        // dd($dataGetNodules);
        return view('backend.pages.members.edit', [
            'admin' => $admin,
            'plans' => $plans,
            'member_id' => $admin->user_id,
            'modulesName' => $modulesName,
            'dataGetNodules' => $dataGetNodules,
            'subjects' => $subjects,
            'trainers' => $trainers,
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
            // MemberModule::create([
            //     'module_id' => $request->serviceID,
            //     'member_id' => $request->memberID,
            //     'membership_id' => $request->membershipID,
            //     'date' => $request->date,
            //     'time' => $request->time,
            //     'module_status' => $request->status,
            //     'remarks' => $request->remarks
            // ]);
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
                    'subject_id' => $request->subject_id,
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
