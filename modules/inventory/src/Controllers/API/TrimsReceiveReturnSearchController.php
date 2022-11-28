<?php


namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;


use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceive;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsIssues\TrimsIssueQtyForStyleAndPO;
use SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrder;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;

class TrimsReceiveReturnSearchController extends Controller
{


    private $trimsIssueQty;

    public function __construct(TrimsIssueQtyForStyleAndPO $trimsIssueQty)
    {
        $this->trimsIssueQty = $trimsIssueQty;
    }

    public function searchReceiveIdForStyle()
    {
        request()->validate([
            'style_name' => 'required',
            'po_no' => 'nullable|array'
        ]);

        $styleName = request('style_name');
        $poNo = request('po_no');

        $receives = TrimsReceive::with('details')
            ->when($styleName, function (Builder $query) use ($styleName) {
                $query->whereHas('details', function (Builder $query) use ($styleName) {
                    $query->where('style_name', $styleName);
                });
            })->when($poNo && count($poNo), function (Builder $query) use ($poNo) {
               $query->whereHas('details', function (Builder $query) use ($poNo) {
                   $query->whereJsonContains('po_no', $poNo);
               });
            })
            ->get();

        return response()->json($receives);
    }



    public function itemStocksForReceive(TrimsReceive $trimsReceive)
    {
        request()->validate([
            'po_no' => 'nullable|array'
        ]);

        $details = $trimsReceive
            ->details()
            ->with(['trimsReceive', 'uom', 'trimsItem',
                'floorDetail:id,name','roomDetail:id,name','rackDetail:id,name',
                'shelfDetail:id,name','binDetail:id,name'])
            ->whereJsonContains('po_no', request('po_no'))
            ->get()
            ->groupBy('item_id')
            ->map(\Closure::fromCallable([$this, 'formatStockData']))
            ->filter(function ($item){
                return $item['stock_qty'] > 0;
            })->values();

        return response()->json($details);
    }

    private function formatStockData($itemWiseData, $itemId)
    {
         $item = collect($itemWiseData)->first();


        $poNo = collect($itemWiseData)->pluck('po_no')->flatten()->unique()->all();

        $poQuantity = PurchaseOrder::whereIn('po_no', $poNo)
            ->sum('po_quantity');

        $receiveQty = collect($itemWiseData)->sum('receive_qty');

        $styleName = $item->style_name;

        $issueQty = $this->trimsIssueQty->getQuantity($styleName, $poNo);

        $stock = $receiveQty - $issueQty;

        return [
            'po_no' => $poNo,
            'style_name' => $styleName,
            'item_id' => $itemId,
            'item_name' => $item->trimsItem->item_group,
            'item_description' => $item->item_description,
            'brand_sup_ref' => $item->brand_sup_ref,
            'item_color' => $item->item_color,
            'item_size' => $item->item_size,
            'uom_id' => $item->uom_id,
            'uom' => $item->uom->unit_of_measurement,
            'return_qty' => 0,
            'floor' => $item->floorDetail->name ?? '',
            'room' => $item->roomDetail->name ?? '',
            'rack' => $item->rackDetail->name ?? '',
            'shelf' =>$item->shelfDetail->name ?? '',
            'bin' => $item->binDetail->name ?? '',
            'floor_id' => $item->floorDetail->id ?? '',
            'room_id' => $item->roomDetail->id ?? '',
            'rack_id' => $item->rackDetail->id ?? '',
            'shelf_id' =>$item->shelfDetail->id ?? '',
            'bin_id' => $item->binDetail->id ?? '',
            'sewing_line_no' => null,
            'order_uniq_id' => $item->order_uniq_id,
            'ship_date' => $item->ship_date,
            'rate' => $item->rate,
            'amount' => $item->amount,
            'receive_qty' => $receiveQty,
            'po_qty' => $poQuantity,
            'gmts_sizes' => null,
            'stock_qty' => $stock,
            'trims_receive_id' => $item->trims_receive_id
        ];
    }

    public function itemDetailsFromReceive(TrimsReceive $trimsReceive)
    {
        $trimsReceive =  $trimsReceive->details()
            ->where('item_id',request('item_id'))
            ->get()
            ->map(function ($item){
                $poNo = collect($item)['po_no'];
                $item['po_qty'] = PurchaseOrder::whereIn('po_no', $poNo)
                    ->sum('po_quantity');
                $item['return_qty'] = 0;
                return $item;
            });

        return response()->json($trimsReceive);
    }

}
