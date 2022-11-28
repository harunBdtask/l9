<?php

namespace SkylarkSoft\GoRMG\Dyeing\Actions;

class TextileOrderAction
{

    /**
     * @param $textileOrders
     * @param $textileOrderDetails
     * @return void
     */
    public function storeDetails($textileOrders, $textileOrderDetails)
    {
        $textileOrders->textileOrderDetails()->createMany($textileOrderDetails);
    }

    /**
     * @param $textileOrders
     * @param $textileOrderDetails
     * @return void
     */
    public function updateDetails($textileOrders, $textileOrderDetails)
    {
        foreach ($textileOrderDetails as $detail) {
            $textileOrders->textileOrderDetails()->updateOrCreate([
                'id' => $detail['id'],
            ], $detail);
        }
    }

}
