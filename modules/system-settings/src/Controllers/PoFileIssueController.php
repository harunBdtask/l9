<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\PoFileIssueModel;
use SkylarkSoft\GoRMG\SystemSettings\Requests\PoFileIssueRequest;

class PoFileIssueController extends Controller
{
    public function index(Request $request)
    {
        $buyers = Buyer::where("pdf_conversion_key", "!=", null)->get();
        $po_file_issues = PoFileIssueModel::filter($request->query('search'))->latest()->paginate();

        return view('system-settings::po_file_issue_settings.po_file_issue', [
            "buyers" => $buyers,
            "po_file_issues" => $po_file_issues,
        ]);
    }

    public function store(PoFileIssueRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $po_file_issue = new PoFileIssueModel();
            $po_file_issue->fill($request->all())->save();
            Session::flash('alert-success', 'Data Saved Successfully!');
        } catch (\Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!{$exception->getMessage()}");
        } finally {
            return back();
        }
    }

    public function destroy($id)
    {
        try {
            PoFileIssueModel::findOrFail($id)->delete();
            Session::flash('alert-success', 'Data deleted successfully!');
        } catch (\Exception $exception) {
            Session::flash('alert-danger', "Something went wrong!{$exception->getMessage()}");
        } finally {
            return back();
        }
    }
}
