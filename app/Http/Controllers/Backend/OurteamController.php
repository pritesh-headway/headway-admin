<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Client;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class OurteamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['Ourteam.create']);

        return view('backend.pages.teams.index', [
            'admins' => Team::where('is_deleted', '0')->orderBy('displayOrder', 'asc')->get(),
        ]);
    }

    public function moveUp($id)
    {
        $team = Team::findOrFail($id);

        $above = Team::where('displayOrder', '<', $team->displayOrder)
            ->orderBy('displayOrder', 'desc')
            ->first();

        if ($above) {
            $temp = $team->displayOrder;
            $team->displayOrder = $above->displayOrder;
            $above->displayOrder = $temp;

            $team->save();
            $above->save();
        }

        return back();
    }

    public function moveDown($id)
    {
        $team = Team::findOrFail($id);

        $below = Team::where('displayOrder', '>', $team->displayOrder)
            ->orderBy('displayOrder', 'asc')
            ->first();

        if ($below) {
            $temp = $team->displayOrder;
            $team->displayOrder = $below->displayOrder;
            $below->displayOrder = $temp;

            $team->save();
            $below->save();
        }

        return back();
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['Ourteam.create']);

        return view('backend.pages.teams.create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['Ourteam.create']);

        $admin = new Team();
        $admin->name = $request->name;
        $admin->position = $request->position;
        $admin->city = $request->city ?? '';
        $admin->dept = $request->dept ?? 'admin';
        $admin->status = $request->status;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('teams'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', __('Team has been created.'));
        return redirect()->route('admin.ourteam.index');
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
        $this->checkAuthorization(auth()->user(), ['Ourteam.edit']);

        $admin = Team::findOrFail($id);
        return view('backend.pages.teams.edit', [
            'admin' => $admin,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['Ourteam.edit']);
        $admin = Team::findOrFail($id);
        $admin->name = $request->name;
        $admin->position = $request->position;
        $admin->city = $request->city ?? '';
        $admin->dept = $request->dept;
        $admin->status = $request->status;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('teams'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', 'Team has been updated.');
        return redirect()->route('admin.ourteam.index'); //back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['Ourteam.delete']);

        $admin = Team::findOrFail($id);
        $admin->is_deleted = '1';
        $admin->save();
        session()->flash('success', 'Team has been deleted.');
        return back();
    }
}