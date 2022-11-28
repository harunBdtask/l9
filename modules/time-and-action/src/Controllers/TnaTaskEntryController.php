<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNATemplate;
use SkylarkSoft\GoRMG\TimeAndAction\Requests\TNATaskEntryRequest;
use Symfony\Component\HttpFoundation\Response;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNAGroup;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNATask;

class TnaTaskEntryController extends Controller
{
    public function groupAdd(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|max:50',
            'sequence' => 'required|numeric'
        ]);

        $data = TNAGroup::query()->create($request->all());

        return response()->json($data, Response::HTTP_OK);
    }

    public function getGroup(int $id): JsonResponse
    {
        $data = TNAGroup::query()->findOrFail($id);

        return response()->json($data, Response::HTTP_OK);
    }

    public function groupList(): JsonResponse
    {
        $data = TNAGroup::all()->map(function ($group) {
            return [
                'id' => $group->id,
                'text' => $group->name,
                'sequence' => $group->sequence,
            ];
        });

        return response()->json($data, Response::HTTP_OK);
    }

    public function taskList(Request $request): JsonResponse
    {
        $data = TNATask::query()->with('group')
            ->orderBy('sequence', 'ASC')
            ->filter($request)
            ->get();

        return response()->json($data, Response::HTTP_OK);
    }

    public function getTask(int $id): JsonResponse
    {
        $data = TNATask::query()->findOrFail($id);

        return response()->json($data, Response::HTTP_OK);
    }

    public function updateTask(int $id, Request $request): JsonResponse
    {
        $this->taskValidation($request);
        try {
            $data = TNATask::query()->find($id);
            $data->update($request->all());
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'request' => $request->all(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function createTask(TNATaskEntryRequest $request): JsonResponse
    {
        try {
            $data = TNATask::query()->create($request->all());
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'request' => $request->all(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteTask(int $id): JsonResponse
    {
        TNATask::query()->findOrFail($id)->delete();
        return response()->json([
            'message' => 'Data Deleted Successfully'
        ], Response::HTTP_OK);
    }

    private function taskValidation($request)
    {
        $request->validate([
            'id' => 'nullable|numeric',
            'user_id' => 'required',
            'task_name' => 'required',
            'task_short_name' => 'required|max:20',
            'group_id' => 'required|numeric',
            'group_sequence' => 'required|numeric',
            'status' => 'required|numeric',
            'sequence' => 'required|numeric',
            'connected_task_id' => $request->get('plan_date_is_editable') == 0 ? 'required|gt:0' : 'sometimes',
            'lead_time_wise_days.*.days' => $request->get('plan_date_is_editable') == 0 ? 'required|numeric' : 'sometimes',
            'lead_time_wise_days.*.lead_time' => $request->get('plan_date_is_editable') == 0 ? 'required|numeric' : 'sometimes',
        ]);
    }

    public function groupWithTask(): JsonResponse
    {
        $data = TNAGroup::with(['tasks' => function ($collection) {
            return $collection->where('task_short_name', '!=', null)
                ->where('status', 1);
        }])->get();
        return response()->json($data, Response::HTTP_OK);
    }

    public function taskSort(Request $request)
    {

        try {
            foreach ($request->all() as $key => $value) {
                TNATask::query()
                    ->find($value['task_id'])
                    ->update(['sequence' => $value['sequence']]);
            }

            return response()->json([
                'msg' => 'Task Sorted SuccessFull'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'msg' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLeadTime(): JsonResponse
    {
        $data = TNATemplate::query()
            ->orderBy('lead_time', 'asc')
            ->distinct('lead_time')
            ->get()
            ->map(function ($value) {
                return [
                    'id' => $value->lead_time,
                    'text' => $value->lead_time
                ];
            });

        return response()->json($data);
    }
}
