<?php

namespace App\Http\Controllers;

use App\Models\ExibitionVisitor;
use DateTime;
use Exception;
// use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExibitionVisitorExport;


class ExibitionVisitorController extends Controller
{
    // Admin index page
    public function index()
    {
        $visitors = ExibitionVisitor::latest()->paginate(20);
        return view('backend.pages.exibition_visitors.index', compact('visitors'));
    }

    // Admin create page
    public function create()
    {
        return view('backend.pages.exibition_visitors.create');
    }

    public function form()
    {
        return view('backend.pages.exibition_visitors.visitor_form');
    }

    // Admin store
    public function store(Request $request)
    {
        $data = $request->validate([
            'event_venue' => 'nullable|string|max:255',
            'jeweller_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'mobile_1' => 'required|string|max:20',
            'mobile_2' => 'nullable|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'enquired_for' => 'required|string|max:255',
            'headway_service' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:1000',
        ]);

        ExibitionVisitor::create($data);

        // Check if it's coming from the homepage or admin
        if ($request->routeIs('exibition_visitors.store')) {
            return redirect()->route('exibition_visitors.form')->with('success', 'Enquiry submitted successfully!');
        }

        return redirect()->route('admin.exibition_visitors.index')->with('success', 'Visitor added successfully!');
    }

    // Admin edit page
    public function edit($id)
    {
        $visitor = ExibitionVisitor::findOrFail($id);
        return view('backend.pages.exibition_visitors.edit', compact('visitor'));
    }

    // Admin update
    public function update(Request $request, $id)
    {
        $visitor = ExibitionVisitor::findOrFail($id);

        $data = $request->validate([
            'event_venue' => 'nullable|string|max:255',
            'jeweller_name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'mobile_1' => 'required|string|max:20',
            'mobile_2' => 'nullable|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'enquired_for' => 'required|string|max:255',
            'headway_service' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $visitor->update($data);

        return redirect()->route('admin.exibition_visitors.index')->with('success', 'Visitor updated successfully!');
    }

    public function view($id)
    {
        $visitor = ExibitionVisitor::findOrFail($id);
        return view('backend.pages.exibition_visitors.view', compact('visitor'));
    }


    // Admin delete
    public function destroy($id)
    {
        $visitor = ExibitionVisitor::findOrFail($id);
        $visitor->delete();

        return redirect()->route('admin.exibition_visitors.index')->with('success', 'Visitor deleted.');
    }

    // Admin export to Excel
    public function export(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ]);

        try {
            $from = (new DateTime($request->from))->setTime(0, 0, 0)->format('Y-m-d H:i:s');
            $to = (new DateTime($request->to))->setTime(23, 59, 59)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid date format',
                'error' => $e->getMessage(),
            ]);
        }

        $data = \App\Models\ExibitionVisitor::whereBetween('created_at', [$from, $to])
            ->get([
                'id',
                'event_venue',
                'jeweller_name',
                'owner_name',
                'email',
                'mobile_1',
                'mobile_2',
                'address',
                'city',
                'enquired_for',
                'headway_service',
                'remarks',
                'created_at'
            ]);

        return Excel::download(new ExibitionVisitorExport($data), 'exibition_visitors_' . now()->format('Ymd_His') . '.xlsx');
    }

}