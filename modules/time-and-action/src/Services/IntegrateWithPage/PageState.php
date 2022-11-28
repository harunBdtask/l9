<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Services\IntegrateWithPage;

use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\TimeAndAction\Enums\IntegrateWithPageTypes;

class PageState
{

    /**
     * @var
     */
    private $state;
    /**
     * @var
     */
    private $factoryId;
    /**
     * @var
     */
    private $buyerId;
    /**
     * @var
     */
    private $orderId;

    /**
     * @var string[]
     */
    private $bindings = [
        IntegrateWithPageTypes::PRICE_QUOTATION => PriceQuotationIntegrate::class,
        IntegrateWithPageTypes::ORDER_ENTRY => OrderIntegrate::class,
        IntegrateWithPageTypes::BUDGET => BudgetIntegrate::class
    ];

    /**
     * @param $page
     */
    private function __construct($page)
    {
        $this->state = $page;
    }

    /**
     * @param $page
     * @return PageState
     */
    public static function setState($page): PageState
    {
        return new static($page);
    }

    /**
     * @param $factoryId
     * @return $this
     */
    public function setFactory($factoryId): PageState
    {
        $this->factoryId = $factoryId;
        return $this;
    }

    /**
     * @param $buyerId
     * @return $this
     */

    public function setBuyer($buyerId): PageState
    {
        $this->buyerId = $buyerId;
        return $this;
    }

    /**
     * @param $orderId
     * @return $this
     */

    public function setOrder($orderId): PageState
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFactoryId()
    {
        return $this->factoryId;
    }

    /**
     * @return mixed
     */
    public function getBuyerId()
    {
        return $this->buyerId;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }


    public function getOrder()
    {
        return Order::query()->where([
            'factory_id' => $this->getFactoryId(),
            'buyer_id' => $this->getBuyerId(),
            'id' => $this->getOrderId()
        ])->first();
    }

    /**
     * @return mixed
     */
    public function get()
    {
        if (!isset($this->bindings[$this->state])) {
            return false;
        }
        return (new $this->bindings[$this->state])->actualDate($this);
    }
}
