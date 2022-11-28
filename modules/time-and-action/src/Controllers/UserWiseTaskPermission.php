<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\TimeAndAction\Actions\BuyerTaskPermission;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNAGroup;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNATemplate;
use SkylarkSoft\GoRMG\TimeAndAction\Models\UserTaskPermission;
use SkylarkSoft\GoRMG\TimeAndAction\Requests\UserWiseTaskPermissionRequest;
use Symfony\Component\HttpFoundation\Response;

class UserWiseTaskPermission extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([], Response::HTTP_OK);
    }

    public function update(Request $request, $buyerId): JsonResponse
    {
        try {
            foreach ($request->get('tasks') as $task) {
                $buyerTaskPermit = [
                    'plan_date_choice' => $task['plan_date_choice'] ?? 0,
                    'actual_date_choice' => $task['actual_date_choice'] ?? 0,
                    'created_by' => \Auth::id(),
                ];
                UserTaskPermission::query()
                    ->where('buyer_id', $buyerId)
                    ->where('task_id', $task['task_id'])
                    ->update($buyerTaskPermit);
            }
            return response()->json(['message' => 'task permitted'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function buyerWiseTask($buyerId): JsonResponse
    {
        try {
            $buyerTasks = UserTaskPermission::query()
                ->where('buyer_id', $buyerId)
                ->get();

            $taskId = $buyerTasks->pluck('task_id');

            $groupWiseData = TNAGroup::query()
                ->whereHas('tasks', function ($collection) use ($taskId) {
                    return $collection->whereIn('id', $taskId);
                })
                ->with(['tasks' => function ($collection) use ($taskId) {
                    return $collection->whereIn('id', $taskId);
                }])->get()->map(function ($collection) use ($buyerTasks) {
                    $tasks = collect($collection->tasks)->map(function (&$taskCollection) use ($buyerTasks) {
                        $task = collect($buyerTasks)->where('task_id', $taskCollection->id)->first();
                        $taskCollection = [
                            'task_id' => $taskCollection->id,
                            'task_name' => $taskCollection->task_name,
                            'task_short_name' => $taskCollection->task_short_name,
                            'group_id' => $taskCollection->group_id,
                            'status' => $taskCollection->status,
                            'plan_date_choice' => (int)$task->plan_date_choice,
                            'actual_date_choice' => (int)$task->actual_date_choice
                        ];
                        return $taskCollection;
                    });
                    unset($collection['tasks']);
                    $collection ['tasks'] = $tasks;
                    return $collection;
                });

            return response()->json($groupWiseData, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
