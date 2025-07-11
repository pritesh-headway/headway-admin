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
use App\Models\OneTimeMeeting;
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

class OneTimeRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['onetimerequest.create']);

        return view('backend.pages.onetime.index', [
            'admins' => OneTimeMeeting::where(['status' => '1'])->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['onetimerequest.create']);

        return view('backend.pages.onetime.create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['onetimerequest.create']);

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
        return redirect()->route('admin.onetime.index');
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
        $this->checkAuthorization(auth()->user(), ['onetimerequest.edit']);
        $admin = OneTimeMeeting::findOrFail($id);
        $pageName = 'backend.pages.onetime.edit';
        // dd($admin);
        return view($pageName, [
            'admin' => $admin,
            'member_id' => $admin->user_id,
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
        $this->checkAuthorization(auth()->user(), ['onetimerequest.edit']);

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
        return redirect()->route('admin.onetime.index'); //back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['onetimerequest.delete']);

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
    public function addUpdateOneTimeMeetingData(Request $request)
    {
        // dd($request);
        try {
            $members = OneTimeMeeting::updateOrInsert(
                [
                    'id' => $request->meeting_id
                ],
                [
                    'user_id' => $request->user_id,
                    'schedule_date' => $request->date,
                    'schedule_time' => $request->time,
                    'call_status' => $request->status,
                    'remarks' => $request->remarks
                ]
            );
            return response()->json(['status' => true]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false]);
        }
    }
}
