<?php

namespace App\Http\Controllers;

use App\Models\MewsCompany;
use App\Models\MewsEnterprise;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MewsCompaniesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $companies = MewsCompany::with(['enterprise', 'motherCompany'])
            ->orderBy('name')
            ->paginate(15);

        return view('mews.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $enterprises = MewsEnterprise::orderBy('name')->get();
        $companies = MewsCompany::orderBy('name')->get();
        
        return view('mews.companies.create', compact('enterprises', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mews_id' => 'required|string|unique:mews_companies,mews_id',
            'enterprise_id' => 'nullable|string',
            'chain_id' => 'nullable|string',
            'identifier' => 'nullable|string',
            'name' => 'required|string|max:255',
            'mother_company_id' => 'nullable|string',
            'telephone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'website_url' => 'nullable|url',
            'invoicing_email' => 'nullable|email',
            'additional_tax_identifier' => 'nullable|string',
            'iata' => 'nullable|string',
            'department' => 'nullable|string',
            'due_interval' => 'nullable|string',
            'reference_identifier' => 'nullable|string',
            'invoice_due_interval' => 'nullable|string',
            'external_identifier' => 'nullable|string',
            'accounting_code' => 'nullable|string',
            'billing_code' => 'nullable|string',
            'notes' => 'nullable|string',
            'tax_identifier' => 'nullable|string',
            'address_line1' => 'nullable|string',
            'address_line2' => 'nullable|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country_code' => 'nullable|string',
            'country_subdivision_code' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $company = MewsCompany::create($validated);

        return redirect()->route('mews-companies.show', $company)
            ->with('success', 'Mews company created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(MewsCompany $mewsCompany): View
    {
        $mewsCompany->load(['enterprise', 'motherCompany']);

        return view('mews.companies.show', [
            'company' => $mewsCompany
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MewsCompany $mewsCompany): View
    {
        $enterprises = MewsEnterprise::orderBy('name')->get();
        $companies = MewsCompany::where('id', '!=', $mewsCompany->id)->orderBy('name')->get();
        
        return view('mews.companies.edit', [
            'company' => $mewsCompany,
            'enterprises' => $enterprises,
            'companies' => $companies
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MewsCompany $mewsCompany): RedirectResponse
    {
        $validated = $request->validate([
            'mews_id' => 'required|string|unique:mews_companies,mews_id,' . $mewsCompany->id,
            'enterprise_id' => 'nullable|string',
            'chain_id' => 'nullable|string',
            'identifier' => 'nullable|string',
            'name' => 'required|string|max:255',
            'mother_company_id' => 'nullable|string',
            'telephone' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'website_url' => 'nullable|url',
            'invoicing_email' => 'nullable|email',
            'additional_tax_identifier' => 'nullable|string',
            'iata' => 'nullable|string',
            'department' => 'nullable|string',
            'due_interval' => 'nullable|string',
            'reference_identifier' => 'nullable|string',
            'invoice_due_interval' => 'nullable|string',
            'external_identifier' => 'nullable|string',
            'accounting_code' => 'nullable|string',
            'billing_code' => 'nullable|string',
            'notes' => 'nullable|string',
            'tax_identifier' => 'nullable|string',
            'address_line1' => 'nullable|string',
            'address_line2' => 'nullable|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'country_code' => 'nullable|string',
            'country_subdivision_code' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $mewsCompany->update($validated);

        return redirect()->route('mews-companies.show', $mewsCompany)
            ->with('success', 'Mews company updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MewsCompany $mewsCompany): RedirectResponse
    {
        $mewsCompany->delete();

        return redirect()->route('mews-companies.index')
            ->with('success', 'Mews company deleted successfully.');
    }
}
