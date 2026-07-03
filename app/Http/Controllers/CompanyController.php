<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CompanyUpdateRequest;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:edit company settings');
    }

    /**
     * Update the company information.
     */
    public function update(CompanyUpdateRequest $request): RedirectResponse
    {
        $company = Company::firstOrNew();

        $validated = $request->validated();
        if ($request->hasFile('company_logo')) {
            if ($company->company_logo && file_exists(public_path($company->company_logo))) {
                unlink(public_path($company->company_logo));
            }
            $logo = $request->file('company_logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('images/company_images'), $logoName);
            $validated['company_logo'] = 'images/company_images/' . $logoName;
        }

        if ($request->hasFile('fav_icon')) {
            if ($company->fav_icon && file_exists(public_path($company->fav_icon))) {
                unlink(public_path($company->fav_icon));
            }
            $favIcon = $request->file('fav_icon');
            $favIconName = time() . '_' . $favIcon->getClientOriginalName();
            $favIcon->move(public_path('images/company_images'), $favIconName);
            $validated['fav_icon'] = 'images/company_images/' . $favIconName;
        }

        $company->fill($validated);
        $company->save();

        return back()->with('status', 'company-updated');
    }
}
