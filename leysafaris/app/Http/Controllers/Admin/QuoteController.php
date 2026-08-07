<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Package;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(): View
    {
        $quotes = Quote::with(['enquiry', 'package', 'createdBy'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.quotes.index', compact('quotes'));
    }

    public function create(Request $request): View
    {
        $enquiry = $request->filled('enquiry_id')
            ? Enquiry::with('package')->findOrFail($request->integer('enquiry_id'))
            : null;

        $enquiries = Enquiry::orderByDesc('created_at')->limit(50)->get(['id', 'name', 'email', 'package_id']);
        $packages = Package::orderBy('title')->get(['id', 'title', 'starting_price', 'currency']);

        return view('admin.quotes.create', compact('enquiry', 'enquiries', 'packages'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'enquiry_id' => ['required', 'exists:enquiries,id'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'title' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'line_items' => ['nullable', 'array'],
            'line_items.*.description' => ['required_with:line_items', 'string', 'max:500'],
            'line_items.*.amount' => ['required_with:line_items', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:draft,sent,accepted,declined,expired'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:today'],
        ]);

        $validated['reference'] = $this->generateReference();
        $validated['created_by'] = auth()->id();
        $validated['status'] = $validated['status'] ?? 'draft';
        $validated['currency'] = $validated['currency'] ?? 'USD';

        $quote = Quote::create($validated);

        $enquiry = Enquiry::find($validated['enquiry_id']);
        if ($enquiry && $enquiry->status === 'new') {
            $enquiry->update(['status' => 'quote_sent']);
        }

        return redirect()->route('admin.quotes.show', $quote)
            ->with('success', 'Quote created successfully.');
    }

    public function show(Quote $quote): View
    {
        $quote->load(['enquiry.package', 'package', 'createdBy']);

        return view('admin.quotes.show', compact('quote'));
    }

    public function edit(Quote $quote): View
    {
        $enquiries = Enquiry::orderByDesc('created_at')->limit(50)->get(['id', 'name', 'email']);
        $packages = Package::orderBy('title')->get(['id', 'title']);

        return view('admin.quotes.edit', compact('quote', 'enquiries', 'packages'));
    }

    public function update(Request $request, Quote $quote): RedirectResponse
    {
        $validated = $request->validate([
            'enquiry_id' => ['required', 'exists:enquiries,id'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'title' => ['required', 'string', 'max:255'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'line_items' => ['nullable', 'array'],
            'line_items.*.description' => ['required_with:line_items', 'string', 'max:500'],
            'line_items.*.amount' => ['required_with:line_items', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:draft,sent,accepted,declined,expired'],
            'valid_until' => ['nullable', 'date'],
            'sent_at' => ['nullable', 'date'],
            'accepted_at' => ['nullable', 'date'],
        ]);

        if ($validated['status'] === 'sent' && ! $quote->sent_at) {
            $validated['sent_at'] = now();
        }

        if ($validated['status'] === 'accepted' && ! $quote->accepted_at) {
            $validated['accepted_at'] = now();
        }

        $quote->update($validated);

        return redirect()->route('admin.quotes.show', $quote)
            ->with('success', 'Quote updated successfully.');
    }

    public function destroy(Quote $quote): RedirectResponse
    {
        $quote->delete();

        return redirect()->route('admin.quotes.index')
            ->with('success', 'Quote deleted successfully.');
    }

    private function generateReference(): string
    {
        $date = now()->format('Ymd');
        $prefix = "LSQ-{$date}-";

        $lastQuote = Quote::where('reference', 'like', $prefix.'%')
            ->orderByDesc('reference')
            ->first();

        if ($lastQuote) {
            $lastSequence = (int) substr($lastQuote->reference, -4);
            $sequence = $lastSequence + 1;
        } else {
            $sequence = 1;
        }

        return $prefix.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
    }
}
