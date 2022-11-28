<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Repositories\SalaryHistoriesRepository;
use SkylarkSoft\GoRMG\HR\Requests\SalaryHistoriesRequest;
use SkylarkSoft\GoRMG\HR\Resources\SalaryHistoryResource;

class SalaryHistoryController extends Controller
{
    public function index()
    {
        $salaryHistoryRepo = new SalaryHistoriesRepository();
        $apiResponse = new ApiResponse($salaryHistoryRepo->paginate(), SalaryHistoryResource::class);
        return $apiResponse->getResponse();
    }

    public function store(SalaryHistoriesRequest $request)
    {
        $salaryHistoryRepo = new SalaryHistoriesRepository();
        $apiResponse = new ApiResponse($salaryHistoryRepo->store($request), SalaryHistoryResource::class);
        return $apiResponse->getResponse();
    }

    public function update($id, SalaryHistoriesRequest $request)
    {
        $salaryHistoryRepo = new SalaryHistoriesRepository();
        $apiResponse = new ApiResponse($salaryHistoryRepo->update($id, $request), SalaryHistoryResource::class);
        return $apiResponse->getResponse();
    }

    public function show($id)
    {
        $salaryHistoryRepo  = new SalaryHistoriesRepository();
        $apiResponse        = new ApiResponse($salaryHistoryRepo->show($id), SalaryHistoryResource::class);
        return $apiResponse->getResponse();
    }

    public function getById($id)
    {
        $salaryHistoryRepo  = new SalaryHistoriesRepository();
        $apiResponse        = new ApiResponse($salaryHistoryRepo->showById($id), SalaryHistoryResource::class);
        return $apiResponse->getResponse();
    }

    public function destroy($id)
    {
        $salaryHistoryRepo  = new SalaryHistoriesRepository();
        $apiResponse        = new ApiResponse($salaryHistoryRepo->destroy($id), SalaryHistoryResource::class);

        session()->flash('success', 'Delete Success.');
        return redirect()->back();
    }

}
