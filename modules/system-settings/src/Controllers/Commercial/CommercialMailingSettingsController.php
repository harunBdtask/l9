<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers\Commercial;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;
use SkylarkSoft\GoRMG\SystemSettings\Models\CommercialSetting;

class CommercialMailingSettingsController extends Controller
{
    public function index()
    {
         $settings = CommercialSetting::first();
         $teamleaders = Team::with('user')->where('project_type','Commercial')->get();

         $status = collect(CommercialSetting::STATUS)->map(function($val, $key){
            return ['id' => $key, 'text' => $val];
        });
        return view('system-settings::commercial.mailing_settings', compact('status','settings','teamleaders'));
    }

    public function store(Request $request)
    {
        try {
            CommercialSetting::updateOrCreate( ['id'=> 1], $request->all());
            Session::flash('alert-success', 'Data Updated successfully');
        } catch(Exception $exception){
            Session::flash('alert-error', 'Something Went Wrong');
        }

        return redirect('/commercial-mailing-variable-settings');
    }
}