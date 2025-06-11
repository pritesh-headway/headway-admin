<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AddOnPurchase;
use App\Models\Blog;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Services\CurlApiService;
use App\Services\FcmNotificationService;

class OrderAddOnController extends Controller
{

    protected $fcmNotificationService;
    protected $curlApiService;
    public function __construct(CurlApiService $curlApiService, FcmNotificationService $fcmNotificationService)
    {
        $this->fcmNotificationService = $fcmNotificationService;
        $this->curlApiService = $curlApiService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['orderaddon.create']);

        return view('backend.pages.addonorder.index', [
            'admins' => AddOnPurchase::select('add_on_purchase_order.id', 'users.name', 'plans.plan_name', 'add_on_services.title', 'add_on_purchase_order.payment_receipt', 'add_on_purchase_order.purchase_status')
                ->leftJoin('plans', 'plans.id', '=', 'add_on_purchase_order.plan_id')
                ->leftJoin('add_on_services', 'add_on_services.id', '=', 'add_on_purchase_order.addon_id')
                ->leftJoin('users', 'users.id', '=', 'add_on_purchase_order.user_id')
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['orderaddon.create']);
        return view('backend.pages.addonorder.create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['orderaddon.create']);

        $admin = new AddOnPurchase();
        $admin->category_id = $request->category_id;
        $admin->title = $request->title;
        $admin->author = $request->author;
        $admin->blog_date = date('Y-m-d', strtotime($request->blog_date));
        $admin->description = $request->description;
        $admin->status = $request->status;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('blogs'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', __('Membership has been created.'));
        return redirect()->route('admin.orderaddon.index');
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
        $this->checkAuthorization(auth()->user(), ['orderaddon.edit']);

        $admin = AddOnPurchase::select('add_on_purchase_order.id', 'users.name', 'plans.plan_name', 'add_on_services.title', 'add_on_purchase_order.payment_receipt', 'add_on_purchase_order.purchase_status', 'add_on_purchase_order.plan_id', 'add_on_purchase_order.status', 'add_on_purchase_order.purchase_status')
            ->leftJoin('plans', 'plans.id', '=', 'add_on_purchase_order.plan_id')
            ->leftJoin('add_on_services', 'add_on_services.id', '=', 'add_on_purchase_order.addon_id')
            ->leftJoin('users', 'users.id', '=', 'add_on_purchase_order.user_id')
            ->findOrFail($id);
        // dd($admin->plan_id);
        $plans = Plan::findOrFail($admin->plan_id);
        return view('backend.pages.addonorder.edit', [
            'admin' => $admin,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['orderaddon.edit']);

        $admin = AddOnPurchase::findOrFail($id);
        $admin->purchase_status = $request->purchase_status;
        // $admin->status = $request->status;
        $admin->save();

        $newData  = json_encode(array());
        if ($request->purchase_status == 'Approved') {
            $message = "Add On Service has been approved by the admin. All details have been successfully verified.";
        } else if ($request->purchase_status == 'Declined') {
            $message = "Add On Service has been rejected by the admin. Please review the submitted details and try again.";
        }
        $body = array('receiver_id' => $admin->user_id, 'title' => $message, 'message' => $message, 'data' => $newData, 'content_available' => true);
        $sendNotification = $this->fcmNotificationService->sendFcmNotification($body);
        // $notifData = json_decode($sendNotification->getContent(), true);
        // if (isset($notifData['status']) && $notifData['status'] == true) {
        //     return $sendNotification->getContent();
        // } else {
        //     return $sendNotification->getContent();
        // }

        session()->flash('success', 'Add On Service Status has been updated.');
        return redirect()->route('admin.orderaddon.index'); //back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['orderaddon.delete']);

        $admin = AddOnPurchase::findOrFail($id);
        $admin->is_deleted = '1';
        $admin->save();
        session()->flash('success', 'Membership has been deleted.');
        return back();
    }
}
