<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Cms;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class CmsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $this->checkAuthorization(auth()->user(), ['cms.create']);
        $plan = Plan::where('is_deleted', '0')->where('status', '1')->pluck('plan_name', 'id')->toArray();
        return view('backend.pages.cms.index', [
            'admins' => Cms::where('is_deleted', '0')->get(),
            'plan' => $plan,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['admin.create']);
        $plan = Plan::where('is_deleted', '0')->where('status', '1')->get();

        return view('backend.pages.cms.create', ['plan' => $plan]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['cms.create']);

        $admin = new Cms();
        $admin->page_name = $request->page_name;
        $admin->description = $request->description;
        $admin->plan_id = $request->plan_id;
        // $admin->status = $request->status;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('cms'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', __('CMS has been created.'));
        return redirect()->route('admin.cms.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cms $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['cms.edit']);
        $plan = Plan::where('is_deleted', '0')->where('status', '1')->get();
        $admin = Cms::findOrFail($id);
        return view('backend.pages.cms.edit', [
            'admin' => $admin,
            'plan' => $plan,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['cms.edit']);

        $admin = Cms::firstOrNew(['plan_id' => $request->plan_id, 'page_name' => $request->page_name]);
        $admin->page_name = $request->page_name;
        $admin->description = $request->description;
        // $admin->plan_id = $request->plan_id;
        // $admin->status = $request->status;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('banners'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', 'CMS has been updated.');
        return redirect()->route('admin.cms.index'); //back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['cms.delete']);

        $admin = Cms::findOrFail($id);
        $admin->is_deleted = '1';
        $admin->save();
        session()->flash('success', 'CMS has been deleted.');
        return back();
    }

    public function getContent($id, $text)
    {
        $page = Cms::where('plan_id', $id)->where('page_name', $text)->first();

        if ($page) {
            return response()->json([
                'success' => true,
                'data' => [
                    'content' => $page->description
                ]
            ]);
        }

        return response()->json(['success' => false, 'data' => null]);
    }
}
