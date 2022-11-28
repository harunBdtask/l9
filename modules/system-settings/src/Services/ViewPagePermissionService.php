<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Services;

use Illuminate\Support\Collection;

class ViewPagePermissionService
{
    private function data(): array
    {
        return [
            [
                'id' => 'PRICE_QUOTATION',
                'value' => 'Price Quotation',
                'view' => [
                    [
                        'id' => 'PRICE_QUOTATION_VIEW',
                        'value' => 'Price Quotation View',
                    ],
                    [
                        'id' => 'PRICE_QUOTATION_COSTING',
                        'value' => 'Price Quotation Costing',
                    ],
                    [
                        'id' => 'PRICE_QUOTATION_VIEW_AN',
                        'value' => 'Price Quotation View for AN',
                    ],
                ],
            ],
            [
                'id' => 'ORDER',
                'value' => 'Order',
                'view' => [
                    [
                        'id' => 'ORDER_VIEW',
                        'value' => 'Order View',
                    ],
                    [
                        'id' => 'COLOR_WISE_SUMMARY_REPORT',
                        'value' => 'Color Wise Summary Report',
                    ],
                    [
                        'id' => 'CUTTING_WORK_ORDER_SHEET',
                        'value' => 'Cutting Work Order Sheet',
                    ],
                ],
            ],
            [
                'id' => 'BUDGET',
                'value' => 'Budget',
                'view' => [
                    [
                        'id' => 'BUDGET_VIEW',
                        'value' => 'Budget View',
                    ],
                    [
                        'id' => 'BUDGET_COSTING_SHEET',
                        'value' => 'Budget Costing Sheet',
                    ],
                    [
                        'id' => 'BUDGET_COSTING_SHEET_V2',
                        'value' => 'Budget Costing Sheet V2',
                    ],
                    [
                        'id' => 'BUDGET_COSTING_SHEET_AKCL',
                        'value' => 'Budget Costing Sheet AKCL',
                    ],
                ],
            ],
            [
                'id' => 'MAIN_FABRIC_BOOKINGS',
                'value' => 'Main Fabric Bookings',
                'view' => [
                    [
                        'id' => 'FABRIC_BOOKINGS_VIEW',
                        'value' => 'Fabric Bookings View',
                    ],
                    [
                        'id' => 'FABRIC_BOOKINGS_SUMMARY',
                        'value' => 'Fabric Bookings Summary',
                    ],
                    [
                        'id' => 'FABRIC_BOOKINGS_SHEET',
                        'value' => 'Fabric Bookings Sheet',
                    ],
                    [
                        'id' => 'FABRIC_BOOKINGS_PURCHASE_ORDER',
                        'value' => 'Fabric Bookings Purchase Order',
                    ],
                ],
            ],
            [
                'id' => 'SHORT_FABRIC_BOOKINGS',
                'value' => 'Short Fabric Bookings',
                'view' => [
                    [
                        'id' => 'SHORT_FABRIC_BOOKINGS_SUMMARY',
                        'value' => 'Short Fabric Bookings Summary',
                    ],

                ],
            ],
            [
                'id' => 'FABRIC_SERVICE_BOOKINGS',
                'value' => 'Fabric Service Bookings',
                'view' => [
                    [
                        'id' => 'FABRIC_SERVICE_BOOKINGS_VIEW',
                        'value' => 'Fabric Service Bookings View',
                    ],
                ],
            ],
            [
                'id' => 'MAIN_TRIMS_BOOKINGS',
                'value' => 'Main Trims Bookings',
                'view' => [
                    [
                        'id' => 'TRIMS_BOOKINGS_SHEET',
                        'value' => 'Trims Bookings Sheet',
                    ],
                    [
                        'id' => 'TRIMS_BOOKINGS_SHEET_V2',
                        'value' => 'Trims Bookings Sheet V2',
                    ],
                    [
                        'id' => 'TRIMS_BOOKINGS_SHEET_V3',
                        'value' => 'Trims Bookings Sheet V3',
                    ],
                    [
                        'id' => 'TRIMS_BOOKINGS_SHEET_V4',
                        'value' => 'Trims Bookings Sheet V4',
                    ],
                    [
                        'id' => 'TRIMS_BOOKINGS_SHEET_V5',
                        'value' => 'Trims Bookings Sheet V5',
                    ],
                    [
                        'id' => 'TRIMS_BOOKINGS_SHEET_V6',
                        'value' => 'Trims Bookings Sheet V6',
                    ],
                    [
                        'id' => 'TRIMS_BOOKINGS_SHEET_V7',
                        'value' => 'Trims Bookings Sheet V7',
                    ],
                    [
                        'id' => 'TRIMS_BOOKINGS_SHEET_V8',
                        'value' => 'Trims Bookings Sheet V8',
                    ],
                ],
            ],
            [
                'id' => 'SHORT_TRIMS_BOOKINGS',
                'value' => 'Short Trims Bookings',
                'view' => [
                    [
                        'id' => 'SHORT_TRIMS_BOOKINGS_SHEET',
                        'value' => 'Short Trims Bookings Sheet',
                    ],
                ],
            ],
            [
                'id' => 'EMBELLISHMENT_WORK_ORDER',
                'value' => 'Embellishment Work Order',
                'view' => [
                    [
                        'id' => 'EMBELLISHMENT_WORK_ORDER_VIEW',
                        'value' => 'Embellishment Work Order View',
                    ],
                ],
            ],

        ];
    }

    /**
     * @param $viewId
     * @return mixed|void
     */
    public function getPageByView($viewId)
    {
        foreach ($this->data() as $page) {
            foreach ($page['view'] as $view) {
                if ($view['id'] === $viewId) {
                    return $page['id'];
                }
            }
        }
    }

    /**
     * @param $pageId
     * @return mixed|null
     */
    public function getPageById($pageId)
    {
        $page = collect($this->data())->where('id', $pageId)->first();
        return $page ? $page['value'] : null;
    }

    /**
     * @param $viewId
     * @return mixed|null
     */
    public function getViewById($viewId)
    {
        $view = collect($this->data())->pluck('view')->collapse()->where('id', $viewId)->first();
        return $view ? $view['value'] : null;
    }

    /**
     * @return Collection
     */
    public function getPages(): Collection
    {
        $data = $this->data();

        return collect($data)->map(function ($view) {
            return [
                'id' => $view['id'],
                'name' => $view['value'],
                'views' => $view['view'],
            ];
        });
    }

    /**
     * @param $id
     * @return Collection
     */
    public function getViews($id): Collection
    {
        $data = $this->data();

        return collect($data)->where('id', $id)->pluck("view")->flatten(1)->map(function ($view) {
            return [
                'id' => $view['id'],
                'name' => $view['value'],
            ];
        });
    }
}
