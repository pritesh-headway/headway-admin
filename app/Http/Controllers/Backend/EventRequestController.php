<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Event;
use App\Models\EventRequest;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class EventRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['event.create']);
        $admin = EventRequest::with('Events', 'Users')->get();
        // dd($admin);
        return view('backend.pages.eventsrequest.index', [
            'admins' => $admin,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['event.create']);

        return view('backend.pages.eventsrequest.create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['event.create']);

        $admin = new Event();
        $admin->event_name = $request->event_name;
        $admin->location = $request->location;
        $admin->description = $request->description;
        $admin->event_address = $request->event_address;
        $admin->event_price = $request->event_price;
        $admin->event_date_time = $request->event_date_time;
        $admin->status = $request->status;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('events'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', __('Event has been created.'));
        return redirect()->route('admin.eventrequest.index');
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
        $this->checkAuthorization(auth()->user(), ['event.edit']);

        $admin = EventRequest::with('Events', 'Users')->where('event_request.event_id', $id)->first();
        // dd($admin['Events']->event_name);
        return view('backend.pages.eventsrequest.edit', [
            'admin' => $admin,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['event.edit']);

        $admin = EventRequest::findOrFail($id);
        $admin->request_status = $request->request_status;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('events'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', 'Request has been updated.');
        return redirect()->route('admin.eventrequest.index'); //back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['event.delete']);

        $admin = Event::findOrFail($id);
        $admin->is_deleted = '1';
        $admin->save();
        session()->flash('success', 'Event has been deleted.');
        return back();
    }
}
