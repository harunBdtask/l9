<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use SkylarkSoft\GoRMG\TimeAndAction\Actions\BuyerTaskPermission;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNATemplate;
use SkylarkSoft\GoRMG\TimeAndAction\Models\TNATemplateDetail;
use SkylarkSoft\GoRMG\TimeAndAction\Services\TemplateCopyService;
use Symfony\Component\HttpFoundation\Response;

class TNATemplateController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $templates = TNATemplate::query()
            ->with('details.task', 'buyer', 'factory')
            ->filter($request)
            ->paginate();

        return response()->json($templates);
    }

    public function store(Request $request): JsonResponse
    {
        $this->validateTemplate($request);

        try {
            $template = TNATemplate::query()->firstOrNew(['id' => $request->input('id')]);
            $template->fill($request->all());
            $template->save();
            $this->response['message'] = $request->input('id') ? S_UPDATE_MSG : S_SAVE_MSG;
            $this->response['data'] = $template;
            $this->statusCode = Response::HTTP_CREATED;
        } catch (\Exception $e) {
            $this->response['message'] = SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $e->getMessage();
            $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->statusCode);
    }

    public function edit($id): JsonResponse
    {
        try {
            $template = TNATemplate::query()
                ->with('details.task', 'buyer', 'factory')
                ->findOrFail($id);
            return response()->json($template, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function storeDetails(TNATemplate $template, Request $request, BuyerTaskPermission $buyerTaskPermission): JsonResponse
    {
        $this->validateTemplateDetails($request);

        try {
            \DB::beginTransaction();
            foreach ($request->all() as $templateTask) {
                $id = $templateTask['template_details_id'] ?? null;
                if ($id) {
                    $template->details()->find($id)->update($templateTask);
                    continue;
                }

                $template->details()->create($templateTask);
            }
            $tasks = collect($request->all())->pluck('task_id');
            $buyerTaskPermission->handle($template->buyer_id, $tasks);
            \DB::commit();
            $this->response['message'] = S_SAVE_MSG;
            $this->statusCode = Response::HTTP_CREATED;
        } catch (\Throwable $e) {
            $this->response['message'] = SOMETHING_WENT_WRONG;
            $this->response['errMsg'] = $e->getMessage();
            $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->response, $this->statusCode);
    }

    public function destroyDetails(TNATemplateDetail $templateDetail): JsonResponse
    {
        try {
            $templateDetail->delete();
            return response()->json(null, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(TNATemplate $template): JsonResponse
    {
        try {
            $template->details()->delete();
            $template->delete();
            return response()->json(null, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function validateTemplate(Request $request)
    {
        $request->validate([
            'factory_id' => 'required',
            'buyer_id' => 'nullable',
            'lead_time' => 'required|integer',
            'tna_for' => 'required'
        ]);
    }

    private function validateTemplateDetails(Request $request)
    {
        $request->validate([
            '*.task_id' => 'required',
            //'*.deadline' => 'required|integer',
            '*.execution_days' => 'required|integer',
            '*.start_from_day_no' => 'required|integer',
            '*.notice_before' => 'required|integer',
            '*.task_sequence' => 'required|integer',
            '*.status' => ['required', Rule::in([1, 2])]
        ]);
    }

    public function templateCopy(Request $request): JsonResponse
    {
        $request->validate([
            'template_id' => 'required|numeric',
            'lead_time' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();
            (new TemplateCopyService)->copy($request);
            DB::commit();
            return response()->json([
                'message' => 'Template Copy Successfully Done'
            ], Response::HTTP_OK);
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile()
            ], Response::HTTP_OK);
        }
    }
}
