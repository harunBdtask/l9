<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\BasicFinance\Models\Project;
use SkylarkSoft\GoRMG\BasicFinance\Models\Unit;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class RatioController extends Controller
{
    public function currentRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.current-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function quickRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.quick-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function workingCapitalRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.working-capital-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function debtToEquityRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.debt-to-equity-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function equityRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.equity-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function debtRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.debt-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function accountRecivableTurnoverRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.account-receivable-turnover-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function daysSalesRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.days-sales-outstanding-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function assetTurnoverRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.asset-turnover-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function inventoryTurnoverRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.inventory-turnover-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function daysSalesInInventoryRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.days-sales-in-inventory-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function accountsPayableTurnoverRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.accounts-payable-turnover-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function grossProfitRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.gross-profit-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function netProfitRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.net-profit-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function returnOnAssetRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.return-on-assets-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function returnOnCapitalEmployeedRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.return-on-capital-employeed-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function returnOnEquityRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.return-on-equity-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function earningPerShareRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.earning-per-share-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function priceEarningsRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.price-earnings-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function fixedChargeCoverageRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.fixed-charge-coverage-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
    public function debtServiceCoverageRatio(Request $request)
    {
        $factoryId = $request->get('factory_id') ?? factoryId();
        $projectId = $request->get('project_id') ?? null;
        $unitId = $request->get('unit_id') ?? null;
        $factories =  Factory::all();
        $projects = $units = [];
//        if ((getRole() === 'super-admin') || (getRole() === 'admin')) {
//            $projects = Project::where('factory_id',$factoryId)->get();
//        }else{
//            $id = (string)(\Auth::id());
//            $projects = Project::query()
//                ->where('factory_id',$factoryId)
//                ->whereJsonContains('user_ids', [$id])
//                ->get();
//        }
        return view('basic-finance::reports.ratio-report.debt-service-coverage-ratio.view', compact(
            'factories',
            'projects',
            'units',
            'factoryId',
            'projectId',
            'unitId'
        ));
    }
}
