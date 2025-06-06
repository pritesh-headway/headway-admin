<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Banner;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use App\Services\CurlApiService;
use App\Services\FcmNotificationService;

class AddOnServiceController extends Controller
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
        $this->checkAuthorization(auth()->user(), ['addonservice.create']);

        return view('backend.pages.addonservice.index', [
            'admins' => Addon::where('is_deleted', '0')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['addonservice.create']);

        return view('backend.pages.addonservice.create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['addonservice.create']);

        $admin = new Addon();
        $admin->title = $request->title;
        $admin->sort_desc = $request->sort_desc;
        $admin->price = $request->price;
        $admin->description = $request->description;
        $admin->on_store_visit = $request->on_store_visit;
        $admin->duration = $request->duration;
        $admin->tax = $request->tax;
        $admin->status = $request->status;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('plans'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', __('Add On Service has been created.'));
        return redirect()->route('admin.addonservice.index');
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
        $this->checkAuthorization(auth()->user(), ['addonservice.edit']);

        $admin = Addon::findOrFail($id);
        return view('backend.pages.addonservice.edit', [
            'admin' => $admin,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['addonservice.edit']);

        $admin = Addon::findOrFail($id);
        $admin->title = $request->title;
        $admin->sort_desc = $request->sort_desc;
        $admin->price = $request->price;
        $admin->description = $request->description;
        $admin->on_store_visit = $request->on_store_visit;
        $admin->duration = $request->duration;
        $admin->status = $request->status;
        $admin->tax = $request->tax;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('plans'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', 'Add On Service has been updated.');
        return redirect()->route('admin.addonservice.index'); //back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['addonservice.delete']);

        $admin = Addon::findOrFail($id);
        $admin->is_deleted = '1';
        $admin->save();
        session()->flash('success', 'Add On Service has been deleted.');
        return back();
    }
}
