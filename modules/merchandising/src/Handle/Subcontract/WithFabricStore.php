<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 10/10/19
 * Time: 12:12 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Subcontract;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\SubcontractDetail;
use SkylarkSoft\GoRMG\Merchandising\Models\SubcontractMaster;

class WithFabricStore
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        try {
            DB::beginTransaction();
            $id = $this->request->id ?? null;
            $subcontract = SubcontractMaster::findOrNew($id);
            $subcontract->buyer_id = $this->request->buyer_id;
            $subcontract->booking_no = $this->request->booking_no;
            $subcontract->po_no = implode(',', $this->request->po_no);
            $subcontract->other_factory_id = $this->request->other_factory_id;
            $subcontract->price = $this->request->price;
            $subcontract->segment = $this->request->segment;
            $subcontract->total_qty = $this->request->total_qty;
            $subcontract->total_price = $this->request->total_price;

            $subcontract->save();
            $subcontract->subcontract_details()->createMany($this->subcontract_details());
            DB::commit();

            return true;
        } catch (\Exceptioin $exception) {
            DB::rollback();

            return false;
        }
    }

    public function subcontract_details()
    {
        $data_array = [];
        $id = $this->request->id ?? null;
        SubcontractDetail::where('subcontract_master_id', $id)->delete();
        foreach ($this->request->color_id as $key => $value) {
            $data_array[$key]['color_id'] = $this->request->color_id[$key];
            $data_array[$key]['fabric_qty'] = $this->request->fabric_qty[$key];
            $data_array[$key]['cutting_qty'] = $this->request->cutting_qty[$key];
            $data_array[$key]['print_qty'] = $this->request->print_qty[$key];
            $data_array[$key]['input_qty'] = $this->request->input_qty[$key];
            $data_array[$key]['output_qty'] = $this->request->output_qty[$key];
            $data_array[$key]['finishing_qty'] = $this->request->finishing_qty[$key];
            $data_array[$key]['rejection_qty'] = $this->request->rejection_qty[$key];
        }

        return $data_array;
    }
}
