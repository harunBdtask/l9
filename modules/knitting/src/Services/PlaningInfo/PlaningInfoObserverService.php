<?php

namespace SkylarkSoft\GoRMG\Knitting\Services\PlaningInfo;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SkylarkSoft\GoRMG\Knitting\Models\PlanningInfo;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PlaningInfoObserverService
{

    const SETTER = "set";
    const GETTER = "get";

    protected $fillable = [
        'programmable_id' => null,
        'programmable_type' => null,
        'details_ids' => null,
        'details' => null,
        'total_qty' => null,
        'knitting_program_ids' => null,
        'program_date' => null,
        'booking_no' => null,
        'booking_type' => null,
        'booking_date' => null,
        'buyer_name' => null,
        'buyer_id' => null,
        'style_name' => null,
        'unique_id' => null,
        'po_no' => null,
        'body_part' => null,
        'color_type' => null,
        'gmt_color' => null,
        'item_color' => null,
        'fabric_description' => null,
        'fabric_gsm' => null,
        'fabric_dia' => null,
        'dia_type' => null,
        'booking_qty' => null,
        'program_qty' => null,
        'production_qty' => null,
        'fabric_nature_id' => null,
        'fabric_nature' => null,
    ];

    private function __construct($programmableType)
    {
        $this->fillable['programmable_type'] = $programmableType;
    }

    public static function for($programmableType): PlaningInfoObserverService
    {
        return new static($programmableType);
    }

    /**
     * @param $method
     * @param $args
     * @return $this
     * @throws Exception
     */
    public function __call($method, $args)
    {
        $getMethodType = substr($method, 0, 3);
        $getPropertyName = Str::snake(substr($method, 3));
        if (!array_key_exists($getPropertyName, $this->fillable)) {
            throw new \RuntimeException("{$getPropertyName} Property Doesn't Exists");
        }

        if ($getMethodType === self::SETTER) {
            $this->fillable[$getPropertyName] = $args[0] ?? null;
        } else {
            return $this->fillable[$getPropertyName];
        }

        return $this;
    }

    /**
     * @return void
     * @throws Throwable
     */

    public function store(): void
    {
        DB::beginTransaction();
        PlanningInfo::query()->updateOrCreate(
            [
                'programmable_id' => $this->fillable['programmable_id'],
                'programmable_type' => $this->fillable['programmable_type'],
                'fabric_description' => $this->fillable['fabric_description'],
                'body_part' => $this->fillable['body_part'],
                'fabric_gsm' => $this->fillable['fabric_gsm']
            ],
            $this->fillable
        );
        DB::commit();
    }
}
