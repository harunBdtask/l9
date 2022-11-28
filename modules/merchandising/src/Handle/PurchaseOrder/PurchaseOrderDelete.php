<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/25/19
 * Time: 1:58 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\PurchaseOrder;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\PoWiseRecapReportTable;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;

class PurchaseOrderDelete
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
            $po = PurchaseOrder::find($this->request->id);
            PoWiseRecapReportTable::where(['purchase_id' => $this->request->id])->delete();
            $po->delete();
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public function ableToDelete()
    {
        $purchase_order = PurchaseOrder::find($this->request->id);
        if ($purchase_order->created_by == Auth::user()->id || (getRole() == 'admin' || getRole() == 'super-admin')) {
            return true;
        }

        return false;
    }
}
