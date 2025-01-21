<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['testimonial.create']);

        return view('backend.pages.testimonials.index', [
            'admins' => Testimonial::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAuthorization(auth()->user(), ['testimonial.create']);

        return view('backend.pages.testimonials.create', []);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['testimonial.create']);

        $admin = new Testimonial();
        $admin->title = $request->title;
        $admin->heading = $request->heading;
        $admin->is_popup = isset($request->is_popup) && $request->is_popup == 'on' ? 1 : 0;
        $admin->desc = $request->desc;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('banners'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', __('Testimonial has been created.'));
        return redirect()->route('admin.testimonial.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['testimonial.edit']);

        $admin = Testimonial::findOrFail($id);
        return view('backend.pages.testimonials.edit', [
            'admin' => $admin,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['testimonial.edit']);
        $admin = Testimonial::findOrFail($id);
        $admin->title = $request->title;
        $admin->heading = $request->heading;
        $admin->is_popup = isset($request->is_popup) && $request->is_popup == 'on' ? 1 : 0;
        $admin->desc = $request->desc;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('banners'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', 'Testimonial has been updated.');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['testimonial.delete']);

        $admin = Testimonial::findOrFail($id);
        $admin->delete();
        session()->flash('success', 'Testimonial has been deleted.');
        return back();
    }
}
