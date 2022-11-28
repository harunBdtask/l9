<?php
/**
 * @author       : Skylarksost Ltd.
 * @developed By : Zannatul Haque Siam
 * @date         : 16-9-2018
 */

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use App\Http\Controllers\Controller;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Handle\Sample\CompositionList;
use SkylarkSoft\GoRMG\Merchandising\Handle\Sample\SampleCreateFormLoad;
use SkylarkSoft\GoRMG\Merchandising\Handle\Sample\SampleDetailsData;
use SkylarkSoft\GoRMG\Merchandising\Handle\Sample\SampleList;
use SkylarkSoft\GoRMG\Merchandising\Handle\Sample\SampleSearch;
use SkylarkSoft\GoRMG\Merchandising\Handle\Sample\SampleStore;
use SkylarkSoft\GoRMG\Merchandising\Models\Sample;
use SkylarkSoft\GoRMG\Merchandising\Models\SampleDetail;
use SkylarkSoft\GoRMG\Merchandising\Requests\SampleDevelopmentRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Currency;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class SampleController extends Controller
{
    public function sampleList()
    {
        $data = (new SampleList())->generate();

        return view('merchandising::sample.sample_list_v3', $data);
    }

    public function sampleCreate()
    {
        $data = (new SampleCreateFormLoad())->load();

        return view('merchandising::sample.create_sample_v3', $data);
    }

    public function sampleStore(SampleDevelopmentRequest $request)
    {
        if ((new SampleStore($request))->handle()) {
            if ($request->id) {
                Session::flash('alert-success', 'Data Updated Successfully');
            } else {
                Session::flash('alert-success', 'Data Created Successfully');
            }

            return redirect('sample');
        }
        Session::flash('alert-danger', 'Something worng!!. Please try again');

        return redirect('sample');
    }

    public function sampleDetails($id)
    {
        return (new SampleDetailsData($id))->get();
    }

    public function edit($id)
    {
        $data['user'] = Auth::user()->id;
        $data['sample_development'] = Sample::find($id);
        $data['sample_development_details'] = SampleDetail::where('sample_id', $id)->get();
        $data['buyer'] = Buyer::pluck('name', 'id');
        $team_leaders = [];
        Team::where('role', 'Leader')->get()->map(function ($teamMember) use (&$team_leaders) {
            return $team_leaders[$teamMember->member->id] = $teamMember->member->first_name . ' ' . $teamMember->member->last_name;
        });
        $data['team'] = $team_leaders;
        $data['currency'] = Currency::pluck('currency_name', 'id');
        $data['merchant'] = User::select(DB::raw("CONCAT(first_name,' ',COALESCE(last_name, ''), ' (' ,email , ') ') AS name"), 'id')->pluck('name', 'id');
        $data['agent'] = BuyingAgentModel::pluck('buying_agent_name', 'id');
        $data['fabrication_list'] = NewFabricComposition::pluck('construction', 'id');
        $data['items'] = GarmentsItem::all()->pluck('name', 'id');

        return view('merchandising::sample.create_sample_v3', $data);
    }

    public function sampleDelete($id)
    {
        try {
            DB::beginTransaction();
            Sample::find($id)->delete();
            DB::commit();
            Session::flash('alert-success', 'Successfully Deleted');

            return redirect('sample');
        } catch (Exception $e) {
            Session::flash('alert-danger', 'Something Went Wrong');

            return redirect('sample');
        }
    }

    public function sampleDevelopmentSearch(Request $request)
    {
        $data['sample_lists'] = (new SampleSearch($request))->get();
        if ($data['sample_lists']) {
            return view('merchandising::sample.sample_list_v3', $data);
        }
        Session::flash('alert-danger', 'Please Select Column For Search');

        return redirect('sample')->withInput();
    }

    public function getCompositionList(Request $request)
    {
        return (new CompositionList($request))->get();
    }

    public function getSamplePdf()
    {
        $user = Auth::user();
        $query = Sample::with('buyer', 'agent', 'sampleDetails', 'dealingMerchant', 'teamLead', 'order');
        get_lists_data_team_wise($user, $query);
        $data['sample_lists'] = $query->orderBy('id', 'desc')->paginate();
        $pdf = PDF::loadView('merchandising::sample.pdf.sample_list_report', $data);

        return $pdf->download('sample_list.pdf');
    }

    public function downloadWorkSampleFile(Sample $sample)
    {
        if (empty($sample) || empty($sample->sample_files)) {
            abort(404);
        }

        return response()->download(storage_path('app/public/' . $sample->sample_files));
    }
}
