<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Blog;
use App\Models\MemberBatch;
use App\Models\Membership;
use App\Models\Plan;
use App\Models\PlanPurchase;
use GuzzleHttp\Psr7\DroppingStream;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Http\JsonResponse;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['membership.create']);

        return view('backend.pages.membership.index', [
            'admins' => Membership::where('is_deleted', '0')->orderBy('id', 'desc')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['membership.create']);
        return view('backend.pages.membership.create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['membership.create']);

        $admin = new Membership();
        $admin->category_id = $request->category_id;
        $admin->title = $request->title;
        $admin->author = $request->author;
        $admin->blog_date = date('Y-m-d', strtotime($request->blog_date));
        $admin->description = $request->description;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('blogs'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', __('Membership has been created.'));
        return redirect()->route('admin.membership.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['membership.edit']);
        $admin = Membership::findOrFail($id);
        $batches = Batch::where('status', '1')->orderBy('id', 'desc')->get();
        $batchDetails = DB::table('members_batch')
            ->where('member_id', $id)
            ->orderBy('id', 'desc')
            ->first();
        // dd($batches);
        $plans = Plan::findOrFail($admin['product_id']);
        return view('backend.pages.membership.edit', [
            'admin' => $admin,
            'plans' => $plans,
            'member_id' => $id,
            'batches' => $batches,
            'batchDetails' => $batchDetails,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $this->checkAuthorization(auth()->user(), ['membership.edit']);
        $admin = Membership::findOrFail($id);
        $admin->membership_status = $request->membership_statuss;
        $admin->save();

        $memberbatch = new MemberBatch();
        $memberbatch->member_id = $id;
        $memberbatch->batch = $request->batch_no;
        $memberbatch->headway_id = $request->headway_id;
        $memberbatch->save();

        $planorder = PlanPurchase::where('user_id', $admin->user_id)
            ->where('plan_id', $admin->product_id)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->first();
        $planorder->purchase_status = $request->membership_status;
        $planorder->save();

        return response()->json(['message' => 'Membership status updated successfully!']);
        // session()->flash('success', 'Membership Status has been updated.');
        // return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['membership.delete']);

        $admin = Membership::findOrFail($id);
        $admin->is_deleted = '1';
        $admin->save();
        session()->flash('success', 'Membership has been deleted.');
        return back();
    }
}
