<?php

namespace App\Http\Controllers;

use App\Models\SmtpSetting;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\SmtpSettingUpdateRequest;

class SmtpSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:edit smtp settings');
    }

    /**
     * Update the SMTP settings.
     */
    public function update(SmtpSettingUpdateRequest $request): RedirectResponse
    {
        $smtpSetting = SmtpSetting::firstOrNew();

        $validated = $request->validated();

        $smtpSetting->fill($validated);
        $smtpSetting->save();

        return back()->with('status', 'email-updated');
    }
}
