<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Constants\ApplicationConstant;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssue;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceiveReturn;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsReceiveReturnDetailsRequest;

class TrimsReceiveReturnController extends Controller
{
    public $response = [];
    public $status = 200;


    public function index()
    {
        $returnSource = TrimsReceiveReturn::RETURNED_SOURCES;
        $trimReceives = TrimsReceiveReturn::query()
            ->with('factory:id,factory_name', 'returnToSupplier:id,name', 'returnToFactory:id,factory_name', 'store:id,name')
            ->orderByDesc('id')->paginate();
        return view('inventory::trims.trims-receive-return-list', compact('trimReceives', 'returnSource'));
    }

    public function create()
    {
        return view('inventory::trims.trims-receive-return');

    }


    public function show(TrimsReceiveReturn $trimsReceiveReturn)
    {
        return $trimsReceiveReturn->load('details', 'details.itemGroup:id,item_group');
    }

    public function filter(Request $request)
    {
        $this->response = [
            [
                'buyer_name'    => 'habib',
                'buyer_id'      => 1,
                'year'          => '2021',
                'unique_id'     => 'UGL-0001',
                'style_name'    => 'St-1',
                'po_no'         => 'Po-32',
                'po_quantity'   => 34,
                'order_uom'     => 'pcs',
                'order_uom_id'  => 1,
                'shipment_date' => '15-3-2021',
            ],
            [
                'buyer_name'    => 'mustafa',
                'buyer_id'      => 2,
                'year'          => '2021',
                'unique_id'     => 'UGL-0002',
                'style_name'    => 'St-2',
                'po_no'         => 'Po-32',
                'po_quantity'   => 34,
                'order_uom'     => 'pcs',
                'order_uom_id'  => 1,
                'shipment_date' => '15-3-2021',
            ],
        ];

        return response()->json($this->response);

    }

    public function store(Request $request)
    {

        try {
            $receiveReturns = new TrimsReceiveReturn($request->all());
            $receiveReturns->save();

            $this->response['message'] = ApplicationConstant::S_STORED;
            $this->response['receive'] = $receiveReturns;
            $this->status = Response::HTTP_CREATED;

        } catch (\Exception $exception) {
            $this->response['message'] = ApplicationConstant::SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $exception->getMessage();
            $this->status = Response::HTTP_BAD_REQUEST;
        }

        return response()->json($this->response, $this->status);
    }

    public function storeDetails(TrimsReceiveReturnDetailsRequest $request, TrimsReceiveReturn $trimsReceiveReturn)
    {
        try {
            DB::beginTransaction();
            collect($request)->each(function ($item) use ($trimsReceiveReturn) {
                $trimsReceiveReturn = $trimsReceiveReturn->details()->findOrNew(null);
                $trimsReceiveReturn->trims_receive_return_id = $item['trims_receive_return_id'];
                $trimsReceiveReturn->order_uniq_id = $item['order_uniq_id'];
                $trimsReceiveReturn->ship_date = $item['ship_date'];
                $trimsReceiveReturn->style_name = $item['style_name'];
                $trimsReceiveReturn->po_no = json_encode($item['po_no']);
                $trimsReceiveReturn->brand_sup_ref = $item['brand_sup_ref'];
                $trimsReceiveReturn->item_id = $item['item_id'];
                $trimsReceiveReturn->item_description = $item['item_description'];
                $trimsReceiveReturn->gmts_sizes = json_encode($item['gmts_sizes']);
                $trimsReceiveReturn->item_color = $item['item_color'];
                $trimsReceiveReturn->item_size = $item['item_size'];
                $trimsReceiveReturn->uom_id = $item['uom_id'];
                $trimsReceiveReturn->return_qty = $item['return_qty'];
                $trimsReceiveReturn->rate = $item['rate'];
                $trimsReceiveReturn->amount = $item['amount'];
                $trimsReceiveReturn->floor = $item['floor_id'];
                $trimsReceiveReturn->room = $item['room_id'];
                $trimsReceiveReturn->rack = $item['rack_id'];
                $trimsReceiveReturn->shelf = $item['shelf_id'];
                $trimsReceiveReturn->bin = $item['bin_id'];
                $trimsReceiveReturn->save();
            });
            DB::commit();
            $this->response['receive'] = $trimsReceiveReturn;
            $this->response['message'] = ApplicationConstant::S_STORED;
            $this->status = Response::HTTP_CREATED;

        } catch (\Exception $exception) {
            DB::rollBack();
            $this->response['message'] = ApplicationConstant::SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $exception->getMessage();
            $this->status = Response::HTTP_BAD_REQUEST;
        }

        return response()->json($this->response, $this->status);
    }

    public function destroy(TrimsReceiveReturn $trimsReceiveReturn): RedirectResponse
    {
        try {
            \DB::beginTransaction();
            $trimsReceiveReturn->delete();
            $trimsReceiveReturn->details()->delete();
            \DB::commit();
            Session::flash('error', 'Data Deleted Successfully');
            $this->response['message'] = ApplicationConstant::S_DELETED;
            return redirect()->back();

        } catch (\Throwable $e) {
            $this->response['message'] = ApplicationConstant::SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $e->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
            return redirect()->back();
        }

    }

    public function validateMainSection(Request $request)
    {
        $request->validate([
            'factory_id'      => 'required',
            'return_date'     => 'required',
            'returned_source' => 'required',
            'returned_to'     => 'required',
//            'store_id' => 'required',
        ]);

        return redirect()->back();
    }
}
