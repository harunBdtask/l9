<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\ShortBookingSettings;

class ShortBookingsSettingsController extends Controller
{
    public function index()
    {
        $short_booking_settings = ShortBookingSettings::first();

        return view('system-settings::short_booking_settings.index', [
            'short_booking_settings' => $short_booking_settings,
        ]);
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'fabric_percentage' => 'required|numeric',
            'trims_percentage' => 'required|numeric',
        ]);
        $bookingSettings = ShortBookingSettings::query()->updateOrCreate(['id' => $id], $request->all());
        Session::flash('alert-success', 'Data updated successfully!!');

        return back();
    }
}
