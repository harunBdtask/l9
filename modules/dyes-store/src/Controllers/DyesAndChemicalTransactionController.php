<?php

namespace SkylarkSoft\GoRMG\DyesStore\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use SkylarkSoft\GoRMG\DyesStore\Services\StockTransactionService;

class DyesAndChemicalTransactionController extends InventoryBaseController
{
    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function makeStockTransaction(Request $request, $id): RedirectResponse
    {
        try {
            (new StockTransactionService($request->get('type'), $id))->handle();
            $this->alert('success', 'Successfully Approved!');
        } catch (Exception $e) {
            $this->alert('danger', $e->getMessage());
        }

        return Redirect::back();
    }
}
