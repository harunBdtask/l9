<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Models\HrGrade;
use SkylarkSoft\GoRMG\HR\Models\HrGroup;
use SkylarkSoft\GoRMG\HR\Repositories\GradeRepository;
use SkylarkSoft\GoRMG\HR\Requests\GradeRequest;
use SkylarkSoft\GoRMG\HR\Resources\GradeResource;
use Symfony\Component\HttpFoundation\Response;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $gradeRepo = new GradeRepository();
        $grades = $gradeRepo->paginate();
        $groups = HrGroup::query()->pluck('name', 'id');
        $groups = $groups->prepend('Select', 0);

        return view('hr::grades.index', [
            'groups' => $groups,
            'grades' => $grades,
            'grade' => null
        ]);
    }

    public function store(GradeRequest $request, HrGrade $hrGrade): JsonResponse
    {
        try {
            $hrGrade->fill($request->all())->save();
            Session::flash('success', "Successfully Created");
            return response()->json([
                'data' => $hrGrade,
                "message" => "Data Created Successfully!"
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id): JsonResponse
    {
        $gradeRepo = (new GradeRepository())->show($id);

        return response()->json($gradeRepo);
    }

    public function edit($id): JsonResponse
    {
        $gradeRepo = new GradeRepository();
        $grade = $gradeRepo->show($id);

        return response()->json($grade);
    }

    public function update(GradeRequest $request, HrGrade $hrGrade): JsonResponse
    {
        try {
            $hrGrade->fill($request->all())->save();

            Session::flash('success', 'Data Updated successfully');

            return response()->json([
                'data' => $hrGrade,
                "message" => "Successfully Created!"
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id): JsonResponse
    {
        $gradeRepo = new GradeRepository();
        $apiResponse = new ApiResponse($gradeRepo->destroy($id), GradeResource::class);
        return $apiResponse->getResponse();
    }

    public function grades($groupId): JsonResponse
    {
        try {
            $grades = HrGrade::query()->where('group_id', $groupId)->get(['id', 'name as text']);
            return response()->json($grades);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
