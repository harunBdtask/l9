<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api;

use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\HR\ApiResponse;
use SkylarkSoft\GoRMG\HR\Repositories\FestivalLeaveRepository;
use SkylarkSoft\GoRMG\HR\Requests\FestivalLeaveRequest;
use SkylarkSoft\GoRMG\HR\Resources\FestivalLeaveResource;

class FestivalLeaveController
{
    public function index()
    {
        $festivalRepo = new FestivalLeaveRepository();
        $festivals = $festivalRepo->paginate();

        return view('hr::festival-leaves.index', [
            'festivals' => $festivals, 
            'festival' => null
        ]);
    }

    public function store(FestivalLeaveRequest $request)
    {
        $festivalRepo = (new FestivalLeaveRepository())->store($request);

        Session::flash('success', 'Data Created successfully');
        
        return redirect()->back();
    }

    public function show($id)
    {
        $festivalRepo = new FestivalLeaveRepository();
        $apiResponse = new ApiResponse($festivalRepo->show($id), FestivalLeaveResource::class);
        return $apiResponse->getResponse();
    }

    public function edit($id) {
        $festivalRepo = new FestivalLeaveRepository();
        $festivals = $festivalRepo->paginate();  
        $festival = $festivalRepo->show($id);

        return view('hr::festival-leaves.index', [
            'festivals' => $festivals, 
            'festival' => $festival
        ]);
    }

    public function update($id, FestivalLeaveRequest $request)
    {
        $festivalRepo = (new FestivalLeaveRepository())->update($request);

        Session::flash('success', 'Data Updated successfully');
        return redirect('hr/festival-leaves');
    }

    public function destroy($id)
    {
        $festivalRepo = (new FestivalLeaveRepository())->destroy($id);

        Session::flash('success', 'Data Deleted successfully');
        return redirect('hr/festival-leaves');
    }
}
