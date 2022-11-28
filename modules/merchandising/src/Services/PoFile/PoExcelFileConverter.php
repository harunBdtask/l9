<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\PoFile;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use SkylarkSoft\GoRMG\Merchandising\Imports\PoFileImport;
use SkylarkSoft\GoRMG\Merchandising\Models\Country;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;

class PoExcelFileConverter
{
    private $quantityMatrix;

    public function convert($filePath): PoExcelFileConverter
    {
        $collection = Excel::toArray(new PoFileImport(), 'po_files/' . $filePath);
        $matrix = [];
        foreach ($collection[0] as $key => $row) {
            if ($key == 0) {
                continue;
            }

            $garmentsItem = GarmentsItem::query()->where('name', trim($row[3]))->first();


            if (!$garmentsItem) {
                continue;
            }

            $style = $row[0] ?? null;
            $poNo = $row[1] ?? null;
            $item = $row[3] ?? null;
            $countryRow = $row[4] ?? null;
            $sizeRow = $row[6] ?? null;
            $colorRow = $row[5] ?? null;
            $itemId = $garmentsItem['id'];

            $color = Color::query()->firstOrCreate(
                ["name" => $row[5]],
                ['status' => 1]
            );
            $size = Size::query()->firstOrCreate(["name" => $row[6]]);

            $country = Country::query()->firstOrCreate(['name' => $countryRow]);

            $sizeId = $size['id'];
            $colorId = $color['id'];

            $countryId = $country['id'];
            $countryCode = $country['iso_alpha_2_code'];

            $xFactoryDate = $row[9] ? Carbon::parse($row[9])->format('Y-m-d') : '';

            $poReceivedDate = $row[2] ? Carbon::parse($row[2])->format('Y-m-d') : '';

            $duplicateIndex = collect($matrix)->search(
                function ($collection) use ($poNo, $itemId, $sizeId, $colorId, $style) {
                    return $collection['po_no'] == $poNo &&
                        $collection['item_id'] == $itemId &&
                        $collection['size_id'] == $sizeId &&
                        $collection['color_id'] == $colorId &&
                        $collection['style'] == $style &&
                        $collection['particulars'] == 'Qty.';

                });

            $attributes = [
                'style' => $style,
                'po_no' => $poNo,
                'item' => $item,
                'size' => $sizeRow,
                'color' => $colorRow,
                'league' => '',
                'item_id' => $itemId,
                'size_id' => $sizeId,
                'color_id' => $colorId,
                'customer' => '',
                'x_factory_date' => $xFactoryDate,
                'remarks' => $row[10] ?? null,
                'fob_price' => format($row[8]) ?? null,
                'po_received_date' => $poReceivedDate,
                'country_id' => $countryId,
                'country_code' => $countryCode,
            ];

            $value = $row[7] ?? 0;

            if ($duplicateIndex !== false) {
                $matrix[$duplicateIndex]['value'] += $value;
            } else {
                $matrix[] = $attributes + [
                        'value' => $value,
                        'particulars' => 'Qty.',
                    ];
                $matrix[] = $attributes + ['value' => null, 'particulars' => 'Rate'];
                $matrix[] = $attributes + ['value' => null, 'particulars' => 'Ex. Cut %'];
                $matrix[] = $attributes + ['value' => null, 'particulars' => 'Plan Cut Qty.'];
                $matrix[] = $attributes + ['value' => null, 'particulars' => 'Article No.'];
            }
        }
        $this->quantityMatrix = $matrix;
        return $this;
    }

    public function getQuantityMatrix()
    {
        return $this->quantityMatrix;
    }

    public function getGroupedColumns($attributes): array
    {
        $poNoWiseGroup = collect($this->getQuantityMatrix())->groupBy(['po_no', 'style'])->all();
        $requestedData = [];
        foreach ($poNoWiseGroup as $po => $itemsGroups) {
            foreach ($itemsGroups as $key => $items) {
                $requestedData [] = [
                    'buyer_id' => $attributes['buyer_id'],
                    'buyer_code' => $attributes['buyer_code'],
                    'po_no' => $po,
                    'style' => $key,
                    'po_quantity' => $this->getPoQuantity($items),
                    'file' => $attributes['file'],
                    'processed' => $attributes['processed'],
                    'quantity_matrix' => json_encode(collect($items)->toArray()),
                    'created_at' => now()
                ];
            }
        }

        return $requestedData;
    }

    public function getPoQuantity($matrix)
    {
        return collect($matrix)->sum('value');
    }

    public function getPoNo($items)
    {
        return Arr::first($items)['po_no'];
    }
}
