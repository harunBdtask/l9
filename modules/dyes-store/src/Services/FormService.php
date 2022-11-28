<?php

namespace SkylarkSoft\GoRMG\DyesStore\Services;

class FormService
{
    public function formatMulDimForm($rows): array
    {
        $data = [];
        $keys = collect($rows)->keys()->toArray();
        $iters = $rows[$keys[0]];
        foreach ($iters as $idx => $iter) {
            foreach ($keys as $key) {
                $data[$idx][$key] = $rows[$key][$idx];
            }
        }
        return $data;
    }
}
