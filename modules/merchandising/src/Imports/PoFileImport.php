<?php

namespace SkylarkSoft\GoRMG\Merchandising\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class PoFileImport implements ToArray
{
    public function array(array $array): array
    {
        return $array;
    }
}
