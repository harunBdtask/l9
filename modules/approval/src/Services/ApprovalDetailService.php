<?php

namespace SkylarkSoft\GoRMG\Approval\Services;

use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Approval\Models\Approval;
use SkylarkSoft\GoRMG\Approval\Models\ApprovalDetail;
use SkylarkSoft\GoRMG\DyesStore\Models\DyesChemicalsReceive;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricServiceBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\TrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\GatePassChallan\GatePasChallan;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseRequisitions\YarnPurchaseRequisition;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortTrimsBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\WorkOrders\EmbellishmentWorkOrder;
use SkylarkSoft\GoRMG\Merchandising\Models\YarnPurchaseOrder;
use Throwable;

class ApprovalDetailService
{
    private $id;
    private $for;
    private $type;
    private $priority;

    private $approvableType = [
        'Order Approval' => Order::class,
        'PO Approval' => PurchaseOrder::class,
        'Budget Approval' => Budget::class,
        'Fabric Approval' => FabricBooking::class,
        'Short Fabric Approval' => ShortFabricBooking::class,
        'Short Trims Approval' => ShortTrimsBooking::class,
        'Trims Approval' => TrimsBooking::class,
        'Yarn Purchase Approval' => YarnPurchaseOrder::class,
        'Yarn Purchase Requisition' => YarnPurchaseRequisition::class,
        'Service Approval' => FabricServiceBooking::class,
        'Embellishment Approval' => EmbellishmentWorkOrder::class,
        'Gate Pass Challan Approval' => GatePasChallan::class,
        'Price Quotation' => PriceQuotation::class,
        'Yarn Store Approval' => YarnReceive::class,
        'Dyes Chemical Store Approval' => DyesChemicalsReceive::class,
    ];

    private function __construct($for)
    {
        $this->for = $for;
    }

    public static function for($for): ApprovalDetailService
    {
        return new static($for);
    }

    public function setId($id): ApprovalDetailService
    {
        $this->id = $id;
        return $this;
    }

    public function setPriority($priority): ApprovalDetailService
    {
        $this->priority = $priority;
        return $this;
    }

    public function setType($type): ApprovalDetailService
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @throws Throwable
     */
    public function store()
    {
        $approval = Approval::query()
            ->where(function ($query) {
                $query->orWhere('user_id', Auth::id())
                    ->orWhere('alternative_user_id', Auth::id());
            })
            ->where('page_name', $this->for)
            ->first();

        throw_if(!$approval, new \Exception('Something went wrong in approval detail store.'));

        return ApprovalDetail::query()->create([
            'approval_detailable_id' => $this->id,
            'approval_detailable_type' => $this->approvableType[$this->for],
            'user_id' => Auth::id(),
            'page_name' => $this->for,
            'priority' => $approval->priority,
            'type' => $this->type
        ]);
    }
}
