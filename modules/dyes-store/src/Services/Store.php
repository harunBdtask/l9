<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services;

use SkylarkSoft\GoRMG\DyesStore\Models\DsItem;
use SkylarkSoft\GoRMG\Inventory\Exceptions\VoucherIdNullException;
use SkylarkSoft\GoRMG\Inventory\Interfaces\RequisitionInterface;
use SkylarkSoft\GoRMG\Inventory\Interfaces\StoreInterface;
use SkylarkSoft\GoRMG\SystemSettings\Models\Stores;
use SkylarkSoft\GoRMG\SystemSettings\Models\Supplier;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

abstract class Store implements StoreInterface, RequisitionInterface
{

    const IN = 'in';
    const OUT = 'out';

    public $store;
    public $type;
    private $id = null;

    public function __construct($store, $type = null)
    {
        $this->store = $store;
        $this->type = $type;
    }

    public static function getStores(): array
    {
        return Stores::all(['id', 'name', 'sym', 'code'])->toArray();
    }

    public function setVoucherId($id)
    {
        $this->id = $id;
    }

    public function commonData(): array
    {
        $items = $this->items();

        return [
            'items' => $this->items(),
            'uoms' => $items->pluck('uom', 'id'),
            'type' => $this->type,
            'store' => $this->store,
            //            'requisitions' => $this->getRequisitions()
        ];
    }

    /**
     * Stock In Voucher Create Data
     * @return array
     */
    public function stockInData(): array
    {
        $suppliers = $this->suppliers();
        return array_merge(
            $this->commonData(),
            ['suppliers' => $suppliers]
        );
    }

    /**
     * Stock Out Voucher Create Data
     * @return array
     */
    public function stockOutData(): array
    {
        $consumers = $this->consumers();
        return array_merge(
            $this->commonData(),
            ['consumers' => $consumers]
        );
    }

    /**
     * @return mixed
     * @throws VoucherIdNullException
     */
    public function stockOutEditData()
    {
        $this->checkIfVoucherIdIsSet();
        $consumers = $this->consumers();
        $voucher = $this->getVoucherById();
        return array_merge(
            $this->commonData(),
            ['consumers' => $consumers, 'voucher' => $voucher]
        );
    }

    /**
     * @return mixed
     * @throws VoucherIdNullException
     */
    public function stockInEditData()
    {
        $this->checkIfVoucherIdIsSet();
        $suppliers = $this->suppliers();
        $voucher = $this->getVoucherById();
        return array_merge(
            $this->commonData(),
            ['suppliers' => $suppliers, 'voucher' => $voucher]
        );
    }

    /**
     * @throws VoucherIdNullException
     */
    private function checkIfVoucherIdIsSet()
    {
        if (is_null($this->id)) {
            throw new VoucherIdNullException('Voucher id is null. Please set the voucher id!');
        }
    }

    private function getVoucherById()
    {
        return InvVoucher::find($this->id);
    }

    public function getRequisitions()
    {
        if ($this->type == 'in') {
            return $this->purchaseRequisitions();
        } elseif ($this->type == 'out') {
            return $this->requisitions();
        }
    }

    public function getStoreName()
    {
        return collect(static::getStores())
            ->where('sym', $this->store)
            ->first()['name'];
    }

    public function getStore()
    {
        return collect(static::getStores())
            ->where('sym', $this->store)
            ->first();
    }

    private function suppliers(): \Illuminate\Support\Collection
    {
        return Supplier::query()->pluck('name', 'id');
    }

    private function items()
    {
        $q = DsItem::with(['uomDetails', 'brand', 'category'])->store($this->store)->withAvlQty();
        if ($this->type == self::OUT) {
            $q->where('barcode', 0);
        }
        return $q->orderBy('name', 'asc')->get(['id', 'name', 'uom', 'avl_qty']);
    }

    private function consumers()
    {
        return User::pluck('first_name', 'id');
    }
}
