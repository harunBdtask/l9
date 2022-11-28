<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services;

use Illuminate\Support\Collection;
use SkylarkSoft\GoRMG\SystemSettings\Models\MachineType;

class MachineTypeService
{
    public static function all(): Collection
    {
//        $types = [
//            [
//                'id' => 'Single Jersey',
//                'text' => 'Single Jersey'
//            ],
//            [
//                'id' => 'Double Jersey',
//                'text' => 'Double Jersey'
//            ],
//            [
//                'id' => 'Fleece',
//                'text' => 'Fleece'
//            ],
//            [
//                'id' => 'Rib',
//                'text' => 'Rib'
//            ],
//            [
//                'id' => 'Interlock',
//                'text' => 'Interlock'
//            ],
//            [
//                'id' => 'Interlock/Reversible',
//                'text' => 'Interlock/Reversible'
//            ],
//            [
//                'id' => 'Auto Striper SJ',
//                'text' => ' Auto Striper SJ'
//            ],
//        ];

        $types = MachineType::query()->get()
            ->map(function ($type) {
                return [
                    'id' => $type->name,
                    'text' => $type->name,
                ];
            });

        return collect($types);
    }
}
