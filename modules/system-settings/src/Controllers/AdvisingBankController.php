<?php
/**
 * Created by PhpStorm.
 * User: Skylark
 * Date: 2/13/2019
 * Time: 11:38 AM
 */

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\SystemSettings\Models\AdvisingBank;

class AdvisingBankController extends Controller
{
    public function index()
    {
        $data['banks'] = AdvisingBank::all();

        return view('system-settings::pages.advising_bank', $data);
    }

    public function store()
    {
        $data = request(['name', 'address']);
        AdvisingBank::firstOrCreate($data);
        session()->flash('alert-success', 'Advising Bank Created');

        return redirect()->back();
    }
}
