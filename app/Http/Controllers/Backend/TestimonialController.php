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
            'admins' => Testimonial::where('is_deleted', '0')->get(),
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
        $admin->description = $request->desc;
        $admin->city = $request->city;
        $admin->rating = $request->rating;
        $admin->status = $request->status;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('testimonials'), $imageName); // Save to 'public/uploads'
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
        $admin->description = $request->desc;
        $admin->city = $request->city;
        $admin->rating = $request->rating;
        $admin->status = $request->status;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('testimonials'), $imageName); // Save to 'public/uploads'
            $admin->image = $imageName;
        }
        $admin->save();

        session()->flash('success', 'Testimonial has been updated.');
        return redirect()->route('admin.testimonial.index'); //back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['testimonial.delete']);

        $admin = Testimonial::findOrFail($id);
        $admin->is_deleted = '1';
        $admin->save();
        session()->flash('success', 'Testimonial has been deleted.');
        return back();
    }
}
