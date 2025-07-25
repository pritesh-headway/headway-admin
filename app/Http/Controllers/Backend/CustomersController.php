<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use App\Services\CurlApiService;
use App\Services\FcmNotificationService;

class CustomersController extends Controller
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
        // $this->checkAuthorization(auth()->user(), ['customer.create']);
        return view('backend.pages.customers.index', [
            'admins' => User::where('is_deleted', '0')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['customer.create']);

        return view('backend.pages.customers.create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['customer.create']);

        $admin = new Client();
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
        return redirect()->route('admin.customers.index');
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
        $this->checkAuthorization(auth()->user(), ['customer.edit']);

        $admin = User::findOrFail($id);
        return view('backend.pages.customers.edit', [
            'admin' => $admin,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['customer.edit']);

        $admin = User::findOrFail($id);
        $admin->is_verify = $request->is_verify;
        $admin->save();

        $newData  = json_encode(array());
        if ($request->is_verify == 1) {
            $message = "" . $admin->name . " has been successfully verified by the admin. All credentials and details have been reviewed and approved.";
        } else {
            $message = "" . $admin->name . " rejected by the admin. All credentials and details have been reviewed and **not approved**.";
        }
        $body = array('receiver_id' => $admin->id, 'title' => $message, 'message' => $message, 'data' => $newData, 'content_available' => true);
        // $sendNotification = $this->fcmNotificationService->sendFcmNotification($body);
        // $notifData = json_decode($sendNotification->getContent(), true);
        // if (isset($notifData['status']) && $notifData['status'] == true) {
        //     return $sendNotification->getContent();
        // } else {
        //     return $sendNotification->getContent();
        // }

        session()->flash('success', 'Customer verify status has been updated.');
        return redirect()->route('admin.customers.index'); //back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['customer.delete']);

        $admin = User::findOrFail($id);
        $admin->is_deleted = '1';
        $admin->save();
        session()->flash('success', 'Customer has been deleted.');
        return back();
    }
}
