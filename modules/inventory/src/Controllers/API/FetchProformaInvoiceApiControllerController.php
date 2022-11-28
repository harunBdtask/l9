<?php

namespace SkylarkSoft\GoRMG\Inventory\Controllers\API;

use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\SystemSettings\Models\Item;
use Symfony\Component\HttpFoundation\Response;

class FetchProformaInvoiceApiControllerController extends Controller
{
    public function __invoke(): \Illuminate\Http\JsonResponse
    {
        $item_category_query = Item::query()->where('item_name', 'Knit Finish Fabrics')->first();

        $item_category = $item_category_query ? $item_category_query->id : null;

        $proformaInvoices = [];

        if ($item_category) {
            $proformaInvoices = ProformaInvoice::query()
                ->where('item_category', $item_category)
                ->get(['id', 'pi_no'])->map(function ($pi) {
                    return [
                        'id' => $pi->pi_no,
                        'text' => $pi->pi_no,
                    ];
                });
        }

        return response()->json($proformaInvoices, Response::HTTP_OK);
    }
}
