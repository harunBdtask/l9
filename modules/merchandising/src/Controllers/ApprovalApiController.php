<?php

namespace SkylarkSoft\GoRMG\Merchandising\Controllers;

use Illuminate\Http\JsonResponse;
use SkylarkSoft\GoRMG\Approval\Models\Approval;

class ApprovalApiController
{
    public function fetchApprovalList($buyerId, $page)
    {
        $query = Approval::query()
            ->with('user:id,screen_name')
            ->where('page_name', $page);

        if ($buyerId) {
            $query->whereRaw('FIND_IN_SET(?,buyer_ids)', [$buyerId]);
        }

        $approval = $query->orderBy('priority')->get();

        $approval = collect($approval)->groupBy('priority')->map(function ($item, $priority) {
            return [
                'user' => collect($item)->pluck('user.screen_name')->implode(', '),
                'priority' => $priority
            ];
        })->values();

        return response()->json($approval);

    }

}
