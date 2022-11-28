<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\YarnTransfer;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssue;
use SkylarkSoft\GoRMG\Inventory\Models\YarnTransfer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Inventory\Requests\YarnTransferRequest;
use Throwable;

class YarnTransferController extends Controller
{
    public function index()
    {
        $supplier = Factory::query()->get();
        $data = YarnTransfer::query()
            ->with(['factory', 'details'])
            ->when(request('challan_no'), function (Builder $builder){
                $builder->where('challan_no', '$'.request('challan_no').'%');
            })
            ->when(request('year'), function (Builder $builder){
                $builder->whereYear('transfer_date', '%'.request('year').'%');
            })
            ->when(request('company_name'), function (Builder $builder){
                $builder->where('factory_id', '%'.request('company_name').'%');
            })
            ->when(request('transfer_date'), function (Builder $builder){
                $builder->where('transfer_date', '%'.request('transfer_date').'%');
            })
            ->when(request('transfer_id'), function (Builder $builder){
                $builder->where('transfer_no','like', '$'.request('transfer_id').'%');
            })
            ->orderByDesc('id')
            ->paginate();

        return view('inventory::yarns.yarn-transfer.index', compact('data', 'supplier'));
    }

    public function create($id = null)
    {
        return view('inventory::yarns.yarn-transfer.create');
    }

    public function store(YarnTransferRequest $request): JsonResponse
    {
        try {
            $id = $request->input('id');
            $transfer = YarnTransfer::query()->firstOrNew(['id' => $id]);
            $transfer->fill($request->all());
            $transfer->save();

            $this->response['data'] = $transfer;
            $this->response['message'] = S_SAVE_MSG;
            $this->statusCode = 201;
        } catch (\Exception $e) {
            $this->response['message'] = E_SAVE_MSG;
            $this->response['errMsg'] = $e->getMessage();
            $this->statusCode = 500;
        }

        return response()->json($this->response);
    }

    public function update(YarnTransfer $transfer, YarnTransferRequest $request)
    {
        try {
            $transfer->update($request->all());
            $this->response['message'] = S_UPDATE_MSG;
        } catch (\Exception $e) {
            $this->response['message'] = E_UPDATE_MSG;
            $this->response['errMsg'] = $e->getMessage();
            $this->statusCode = 500;
        }
    }


    public function show(YarnTransfer $transfer): JsonResponse
    {
        $this->response = $transfer;
        return response()->json($this->response);
    }



    /**
     * @throws Throwable
     */
    public function delete($id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $transfer = YarnTransfer::query()->findOrFail($id);
            if ($transfer->details()->count() != 0) {
                session()->flash('danger', 'Please delete details first!');
            } else {
                $transfer->delete();
                session()->flash('success', 'Successfully Deleted');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('danger', 'Deletion Failed!');
        }

        return redirect()->back();
    }

    public function view($id)
    {
        $data = YarnTransfer::with([
            'factory','details.composition', 'details.yarn_count', 'details.type', 'details.floor', 'details.room', 'details.rack', 'details.shelf', 'details.bin', 'details.store', 'details.uom'
        ])->findOrFail($id);

        return view('inventory::yarns.yarn-transfer.view', compact('data'));
    }

    public function print($id)
    {
        $data = YarnTransfer::with([
            'factory','fromStore','toStore','details.composition', 'details.yarn_count', 'details.type', 'details.floor', 'details.room', 'details.rack', 'details.shelf', 'details.bin', 'details.store', 'details.uom'
        ])->findOrFail($id);

        return view('inventory::yarns.yarn-transfer.print', compact('data'));
    }
}
