<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\YarnPurchaseOrder;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface Searchable
{
    public function search(Request $request): Collection;
}
