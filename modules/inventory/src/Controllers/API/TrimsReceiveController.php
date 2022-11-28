<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use Throwable;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Constants\ApplicationConstant;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceive;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsReceiveRequest;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsInfoSearchInterface;
use SkylarkSoft\GoRMG\Inventory\Requests\TrimsReceiveDetailsRequest;

class TrimsReceiveController extends Controller
{
    public $response = [];
    public $status = 200;

    public function index(Request $request)
    {
        $trimReceives = TrimsReceive::query()
            ->orderByDesc('id')
            ->search($request->input('search'))
            ->paginate();

        return view('inventory::trims.pages.trims-receives', compact('trimReceives'));
    }

    public function create()
    {
        return view('inventory::trims.trims-receive');
    }

    public function searchTrimsFromPIorBooking(Request $request, TrimsInfoSearchInterface $infoSearch): JsonResponse
    {
        $this->response = $infoSearch->search();
        return response()->json($this->response);
    }

    /**
     * @throws Throwable
     */
    public function store(TrimsReceiveRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $trimsReceiveId = $request->input('id');
            $trimsReceive = TrimsReceive::query()->findOrNew($trimsReceiveId);
            $trimsReceive->fill($request->all())->save();
            DB::commit();

            $this->response['message'] = $request->input('id') ? ApplicationConstant::S_UPDATED : ApplicationConstant::S_STORED ;
            $this->response['receive'] = $trimsReceive;
            $this->status = Response::HTTP_CREATED;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->response['message'] = ApplicationConstant::SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $e->getMessage();
            $this->status = Response::HTTP_BAD_REQUEST;
        }

        return response()->json($this->response, $this->status);
    }

    /**
     * @throws Throwable
     */
    public function storeDetails(TrimsReceiveDetailsRequest $request, TrimsReceive $receive): JsonResponse
    {
        //dd($request->all());
        try {
            DB::beginTransaction();
            collect($request->all())->each(function ($detail) use ($receive) {
                $id = isset($detail['id']) ? $detail['id'] : null;
                $trimsReceiveDetails = $receive->details()->findOrNew($id);
                $trimsReceiveDetails->fill($detail)->save();
            });
            DB::commit();

            $this->response['message'] = ApplicationConstant::S_STORED;
            $this->status = 201;

        } catch (\Exception $e) {

            $this->response['message'] = ApplicationConstant::SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $e->getMessage();
            $this->response['line'] = $e->getLine();
            $this->response['file'] = $e->getFile();
            $this->status = Response::HTTP_BAD_REQUEST;
        }

        return response()->json($this->response, $this->status);
    }

    public function show(TrimsReceive $receive): JsonResponse
    {
        $this->response = $receive->load('details','details.trimsItem');

        return response()->json($this->response);
    }

    /**
     * @throws Throwable
     */
    public function delete(TrimsReceive $receive): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $receive->delete();
            $receive->details()->delete();
            DB::commit();

            $this->response['message'] = ApplicationConstant::S_DELETED;

            Session::flash('success', 'Data Deleted successfully!');

        } catch (Throwable $e) {
            DB::rollBack();
            Session::flash('error', "Something went wrong!{$e->getMessage()}");
        }

        return redirect()->back();
    }

    public function destroyDetails(TrimsReceiveDetail $trimsReceiveDetail): JsonResponse
    {
        try {
            $trimsReceiveDetail->delete();

            $this->response['message'] = ApplicationConstant::S_DELETED;
        } catch (\Exception $exception) {
            $this->response['message'] = ApplicationConstant::SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $exception->getMessage();
            $this->status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->status);
    }

    public function view($id)
    {
        $trimsReceive = TrimsReceive::query()
            ->with([
                'details'
            ])
            ->findOrFail($id);
        return view('inventory::trims.pages.trims-receive-view',[
            'trimsReceive' => $trimsReceive
        ]);
    }

    public function pdf($id)
    {
        $trimsReceive = TrimsReceive::query()
            ->with([
                'details'
            ])
            ->findOrFail($id);
        $pdf = PDF::loadView('inventory::trims.pages.trims-receive-pdf',
            compact('trimsReceive'))
            ->setPaper('a4')
            ->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);

        return $pdf->stream('trims_receive_view.pdf');
    }
}
