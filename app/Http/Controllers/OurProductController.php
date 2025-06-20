<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OurProduct;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;

class OurProductController extends Controller
{
    public function index()
    {
        // $this->checkAuthorization(auth()->user(), ['ourproduct.view']);

        return view('backend.pages.our_products.index', [
            'products' => OurProduct::all(),
        ]);
    }

    public function create()
    {
        // $this->checkAuthorization(auth()->user(), ['ourproduct.create']);

        return view('backend.pages.our_products.create');
    }

    public function store(Request $request)
    {
        // $this->checkAuthorization(auth()->user(), ['ourproduct.create']);

        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'photo' => 'required|image',
            'product_banner' => 'required|image',
            'play_store' => 'nullable|url',
            'app_store' => 'nullable|url',
            'web_url' => 'nullable|url',
            'tagline' => 'nullable|string',
        ]);

        $product = new OurProduct();
        $product->title = $request->title;
        $product->desc = $request->desc;
        $product->play_store = $request->play_store;
        $product->app_store = $request->app_store;
        $product->web_url = $request->web_url;
        $product->tagline = $request->tagline;

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('products'), $imageName);
            $product->photo = $imageName;
        }
        if ($request->hasFile('product_banner')) {
            $image = $request->file('product_banner');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('products'), $imageName);
            $product->product_banner = $imageName;
        }

        $product->save();

        session()->flash('success', 'Product has been created.');
        return redirect()->route('admin.our_products.index');
    }

    public function edit(int $id): Renderable
    {
        // $this->checkAuthorization(auth()->user(), ['ourproduct.edit']);

        $product = OurProduct::findOrFail($id);
        return view('backend.pages.our_products.edit', compact('product'));
    }

    public function update(Request $request, int $id)
    {
        // $this->checkAuthorization(auth()->user(), ['ourproduct.edit']);

        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'photo' => 'nullable|image',
            'product_banner' => 'nullable|image',
            'play_store' => 'nullable|url',
            'app_store' => 'nullable|url',
            'web_url' => 'nullable|url',
            'tagline' => 'nullable|string',
        ]);

        $product = OurProduct::findOrFail($id);
        $product->title = $request->title;
        $product->desc = $request->desc;
        $product->play_store = $request->play_store;
        $product->app_store = $request->app_store;
        $product->web_url = $request->web_url;
        $product->tagline = $request->tagline;

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('products'), $imageName);
            $product->photo = $imageName;
        }

        if ($request->hasFile('product_banner')) {
            $image = $request->file('product_banner');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('products'), $imageName);
            $product->product_banner = $imageName;
        }


        $product->save();

        session()->flash('success', 'Product has been updated.');
        return redirect()->route('admin.our_products.index');
    }

    public function destroy(int $id)
    {
        // $this->checkAuthorization(auth()->user(), ['ourproduct.delete']);

        $product = OurProduct::findOrFail($id);
        $product->delete();

        session()->flash('success', 'Product has been deleted.');
        return back();
    }
}
