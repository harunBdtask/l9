<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Repositories\EmployeeRepository;
use SkylarkSoft\GoRMG\HR\Resources\EmployeeResourse;

class EmployeeStaffController extends Controller
{
    /**
     * @var EmployeeRepository
     */
    private $repository;

    /**
     * EmployeeStaffController constructor.
     * @param EmployeeRepository $repository
     */
    public function __construct(EmployeeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return ApiResponse::create($this->repository->paginateStaff(), EmployeeResourse::class)->getResponse();
    }

    public function search(Request $request)
    {
        // searchStaffs
        return ApiResponse::create($this->repository->searchStaffs($request), EmployeeResourse::class)->getResponse();

    }
}
