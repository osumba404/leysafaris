<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Package;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request): View
    {
        $query = Enquiry::with(['package', 'user', 'assignedTo']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->integer('assigned_to'));
        }

        $enquiries = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $admins = User::whereIn('role', ['admin', 'super_admin'])->orderBy('name')->get(['id', 'name']);

        return view('admin.enquiries.index', compact('enquiries', 'admins'));
    }

    public function create(): View
    {
        $packages = Package::orderBy('title')->get(['id', 'title']);
        $admins = User::whereIn('role', ['admin', 'super_admin'])->orderBy('name')->get(['id', 'name']);

        return view('admin.enquiries.create', compact('packages', 'admins'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateEnquiry($request);
        $validated['status'] = $validated['status'] ?? 'new';
        $validated['source'] = $validated['source'] ?? 'admin';

        Enquiry::create($validated);

        return redirect()->route('admin.enquiries.index')
            ->with('success', 'Enquiry created successfully.');
    }

    public function show(Enquiry $enquiry): View
    {
        $enquiry->load(['package', 'user', 'assignedTo', 'quotes']);

        return view('admin.enquiries.show', compact('enquiry'));
    }

    public function edit(Enquiry $enquiry): View
    {
        $packages = Package::orderBy('title')->get(['id', 'title']);
        $admins = User::whereIn('role', ['admin', 'super_admin'])->orderBy('name')->get(['id', 'name']);

        return view('admin.enquiries.edit', compact('enquiry', 'packages', 'admins'));
    }

    public function update(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $validated = $this->validateEnquiry($request);

        $enquiry->update($validated);

        return redirect()->route('admin.enquiries.show', $enquiry)
            ->with('success', 'Enquiry updated successfully.');
    }

    public function destroy(Enquiry $enquiry): RedirectResponse
    {
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')
            ->with('success', 'Enquiry deleted successfully.');
    }

    private function validateEnquiry(Request $request): array
    {
        return $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'preferred_destinations' => ['nullable', 'string', 'max:500'],
            'travel_dates' => ['nullable', 'string', 'max:255'],
            'group_size' => ['nullable', 'integer', 'min:1', 'max:100'],
            'budget_range' => ['nullable', 'string', 'max:100'],
            'special_interests' => ['nullable', 'string', 'max:1000'],
            'message' => ['nullable', 'string', 'max:5000'],
            'status' => ['nullable', 'string', 'in:new,contacted,quote_sent,negotiation,confirmed,lost'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'admin_notes' => ['nullable', 'string'],
            'source' => ['nullable', 'string', 'max:100'],
        ]);
    }
}
