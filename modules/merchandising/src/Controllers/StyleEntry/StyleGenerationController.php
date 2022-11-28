<?php


namespace SkylarkSoft\GoRMG\Merchandising\Controllers\StyleEntry;


use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Merchandising\Actions\StyleEntry\StyleGenerationAction;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Transform\PriceQuotation\PriceQuotationTransformAsOrder;
use Throwable;

class StyleGenerationController extends Controller
{
    /**
     * @param PriceQuotation $priceQuotation
     * @param Request $request
     * @param StyleGenerationAction $styleGenerationAction
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(PriceQuotation $priceQuotation, Request $request, StyleGenerationAction $styleGenerationAction): RedirectResponse
    {
        try {
            $styleGenerationAction->forOrder($priceQuotation, new PriceQuotationTransformAsOrder());
            Session::flash('alert-success', 'Order Generated Successfully!');
        } catch (\Exception $exception) {
            Session::flash('alert-danger', 'Something Went Wrong!');
        } finally {
            return back();
        }
    }
}
