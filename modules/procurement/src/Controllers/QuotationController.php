<?php

namespace SkylarkSoft\GoRMG\Procurement\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Procurement\Models\ProcureQuotation;
use SkylarkSoft\GoRMG\Procurement\Requests\QuotationFormRequest;
use Symfony\Component\HttpFoundation\Response;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $quotations = ProcureQuotation::query()
            ->with(['item', 'supplier', 'uom'])
            ->when($request->get('search'), function ($query) use ($request) {
                $query->whereHas('item', function ($query) use ($request) {
                    return $query->where('item_group', 'LIKE', '%'.$request->get('search').'%');
                })
                 ->orWhereHas('supplier', function ($query) use ($request) {
                     return $query->Where('name', 'like', '%'.$request->get('search').'%');
                 });
            })
            ->latest()->paginate();

        return view('procurement::quotations.index', compact('quotations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('procurement::quotations.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuotationFormRequest $request): JsonResponse
    {
        try {
            $procureQuotation = new ProcureQuotation();
            $procureQuotation->fill($request->all())->save();

            return response()->json([
                'message' => 'Quotation saved successfully',
                'data' => $procureQuotation,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quotation = ProcureQuotation::find($id);

        return view('procurement::quotations.view', compact('quotation'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $procureQuotation = ProcureQuotation::find($id);

            return response()->json([
                'message' => 'Quotation fetched successfully',
                'data' => $procureQuotation,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(QuotationFormRequest $request, $id)
    {
        try {
            $procureQuotation = ProcureQuotation::find($id);
            $procureQuotation->fill($request->all())->save();

            return response()->json([
                'message' => 'Quotation updated successfully',
                'data' => $procureQuotation,
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $procureQuotation = ProcureQuotation::find($id);
            $procureQuotation->delete();

            Session::flash('success', 'Procurement Quotation deleted successfully');
        } catch (Exception $exception) {
            Session::flash('error', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
