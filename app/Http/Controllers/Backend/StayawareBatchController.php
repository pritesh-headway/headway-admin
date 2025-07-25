<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\RevisionBatch;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class StayawareBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['stayaware.create']);

        return view('backend.pages.stayawarebatch.index', [
            'admins' => RevisionBatch::where('status', '1')->where('form_type', 'Stay_Aware')->whereNotIn('revison_batch_status', ['Approved'])->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['stayaware.edit']);
        $admin = RevisionBatch::findOrFail($id);
        return view('backend.pages.stayawarebatch.edit', [
            'admin' => $admin,
            'roles' => Role::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['stayaware.edit']);

        $admin = RevisionBatch::findOrFail($id);
        $admin->revison_batch_status = $request->revison_batch_status;
        $admin->plan_id = 11;
        $admin->save();

        session()->flash('success', 'Stay Aware Batch has been updated.');
        return redirect()->route('admin.stayawarebatch.index'); //back();
    }
}
