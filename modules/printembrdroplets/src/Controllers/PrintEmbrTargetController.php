<?php

namespace SkylarkSoft\GoRMG\Printembrdroplets\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\PrintFactoryTable;
use SkylarkSoft\GoRMG\Printembrdroplets\Models\PrintEmbrTarget;
use Carbon\Carbon;
use Session, DB;

class PrintEmbrTargetController extends Controller
{
   
    public function printEmbroideryTarget()
    {        
        $target_date = request('target_date') ?? operationDate();

        $print_tables = PrintFactoryTable::with([
            'print_embr_target' => function($query) use ($target_date) {
                $query->where('target_date', $target_date);
        }])->get();
     
        return view('printembrdroplets::forms.print_embr_target', compact('print_tables', 'target_date'));
    }

    public function printEmbroideryTargetPost(Request $request)
    {
        try {

            if ($request->target_date != operationDate()) {
                Session::flash('error', 'You can change only today target');
                DB::rollback();
            }

            DB::beginTransaction();           
            foreach ($request->print_factory_table_ids as $key => $printFactoryTableId) {                
                PrintEmbrTarget::updateOrCreate([
                    'target_date' => $request->target_date,
                    'print_factory_table_id' => $printFactoryTableId
                ],[
                    'man_power' => $request->man_power[$key] ?? 0,
                    'target_qty' => $request->target_qty[$key] ?? 0,
                    'working_hour' => $request->working_hour[$key] ?? 0,
                    'remarks' => $request->remarks[$key] ?? ''
                ]);               
            }
            DB::commit();
            Session::flash('success', S_UPDATE_MSG);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
            DB::rollback();
        }
        return redirect()->back();
    }

}