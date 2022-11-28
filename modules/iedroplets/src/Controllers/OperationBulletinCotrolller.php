<?php

namespace SkylarkSoft\GoRMG\Iedroplets\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use SkylarkSoft\GoRMG\SystemSettings\Models\Floor;
use SkylarkSoft\GoRMG\SystemSettings\Models\Line;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Iedroplets\Models\OperationBulletin;
use SkylarkSoft\GoRMG\Iedroplets\Models\OperationBulletinDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\MachineType;
use SkylarkSoft\GoRMG\SystemSettings\Models\OperatorSkill;
use SkylarkSoft\GoRMG\SystemSettings\Models\Task;
use SkylarkSoft\GoRMG\SystemSettings\Models\GuideOrFolder;
use SkylarkSoft\GoRMG\Iedroplets\Requests\OperationBulletinRequest;
use PDF;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class OperationBulletinCotrolller extends Controller
{

    public function index()
    {
        $operation_bulletins = OperationBulletin::orderBy('id', 'desc')->paginate();

        return view('iedroplets::pages.operation_bulletins', [
            'operation_bulletins' => $operation_bulletins
        ]);
    }

    public function create()
    {
        $floors = Floor::orderBy('sort', 'asc')->pluck('floor_no', 'id')->all();
        $tasks = Task::orderBy('id', 'asc')->pluck('name', 'id')->all();
        $machine_types = MachineType::orderBy('id', 'asc')->pluck('name', 'id')->all();
        $operator_skills = OperatorSkill::orderBy('id', 'asc')->pluck('name', 'id')->all();
        $guide_or_folders = GuideOrFolder::orderBy('id', 'asc')->pluck('name', 'id')->all();

        return view('iedroplets::forms.operation_bulletin', [
            'operation_bulletin' => null,
            'floors' => $floors,
            'buyers' => [],
            'tasks' => $tasks,
            'machine_types' => $machine_types,
            'operator_skills' => $operator_skills,
            'guide_or_folders' => $guide_or_folders
        ]);
    }

    public function store(OperationBulletinRequest $request)
    {
        try {

            DB::transaction(function () use ($request) {

                $operationBulletinData = array_filter($this->getOperationBulletinBasicData($request->all()));
                $operationBulletin = OperationBulletin::create($operationBulletinData);

                if ($operationBulletin && $request->hasFile('sketch')) {

                    $time = time();
                    $file = $request->sketch;
                    $file->storeAs('sketch_images', $time . $file->getClientOriginalName());
                    $operationBulletin->sketch = $time . $file->getClientOriginalName();
                    $operationBulletin->save();
                }

                $operationBulletinDetailsData = $this->getOperationBulletinDetailData($operationBulletin, $request->all());

                if (count($operationBulletinDetailsData)) {
                    OperationBulletinDetail::insert($operationBulletinDetailsData);
                }
            });

            Session::flash('success', S_SAVE_MSG);
        } Catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect('/operation-bulletins');
    }

    private function getOperationBulletinBasicData($inputData = [])
    {
        $keys = (new OperationBulletin)->getFillable();
        $data = [];

        foreach ($inputData as $key => $value) {
            if (in_array($key, $keys)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    private function getOperationBulletinDetailData(OperationBulletin $operationBulletin, $inputData = [])
    {
        $keys = (new OperationBulletinDetail)->getFillable();
        $data = [];

        foreach ($inputData as $key => $values) {
            if (in_array($key, $keys)) {
                $index = 0;
                foreach ($values as $value) {
                    $data[$index][$key] = $value;
                    $data[$index]['operation_bulletin_id'] = $operationBulletin->id;
                    $data[$index]['operation_bulletin_id'] = $operationBulletin->id;
                    $data[$index]['factory_id'] = $operationBulletin->factory_id;
                    $index++;
                }
            }
        }

        return $data;
    }

    public function show()
    {
        $operation_bulletin = OperationBulletin::with('operationBulletinDetails', 'order')
            ->findOrFail(request()->get('id'));
        
        return view('iedroplets::pages.operation_bulletin_show', [
            'operation_bulletin' => $operation_bulletin
        ]);
    }

    public function edit($id)
    {
        $operation_bulletin = OperationBulletin::with('operationBulletinDetails')->findOrFail($id);

        $floors = Floor::orderBy('sort', 'asc')->pluck('floor_no', 'id')->all();
        $buyers = Buyer::where('id', $operation_bulletin->buyer_id)->pluck('name', 'id');
        $tasks = Task::orderBy('id', 'asc')->pluck('name', 'id')->all();
        $machine_types = MachineType::orderBy('id', 'asc')->pluck('name', 'id')->all();
        $operator_skills = OperatorSkill::orderBy('id', 'asc')->pluck('name', 'id')->all();
        $guide_or_folders = GuideOrFolder::orderBy('id', 'asc')->pluck('name', 'id')->all();

        return view('iedroplets::forms.operation_bulletin', [
            'operation_bulletin' => $operation_bulletin,
            'floors' => $floors,
            'buyers' => $buyers,
            'tasks' => $tasks,
            'machine_types' => $machine_types,
            'operator_skills' => $operator_skills,
            'guide_or_folders' => $guide_or_folders
        ]);
    }

    public function update($id, OperationBulletinRequest $request)
    {
        $operationBulletin = OperationBulletin::findOrFail($id);

        DB::transaction(function () use ($operationBulletin, $request) {
            $operationBulletinData = array_filter($this->getOperationBulletinBasicData($request->all()));
            $operationBulletin->update($operationBulletinData);

            if ($operationBulletin && $request->hasFile('sketch')) {

                $time = time();
                $file = $request->sketch;
                $file->storeAs('sketch_images', $time . $file->getClientOriginalName());
                $operationBulletin->sketch = $time . $file->getClientOriginalName();
                $operationBulletin->save();
            }

            $operationBulletin->operationBulletinDetails()->delete();
            $detailData = $this->getOperationBulletinDetailData($operationBulletin, $request->all());

            if (count($detailData)) {
                OperationBulletinDetail::insert($detailData);
            }
        });

        return redirect('/operation-bulletins');
    }

    public function destroy($id)
    {
        try {
            $task = OperationBulletin::findOrFail($id);
            $task->delete();

            Session::flash('success', S_DELETE_MSG);
        } Catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect('operation-bulletins');
    }

    public function searchOperationBulletin(Request $request)
    {
        $q = $request->q;
        if ($q == '') {
            return redirect('operation-bulletins');
        }
        $buyers_query = Buyer::orderBy('id', 'ASC')->where('name', 'like', '%' . $q . '%');      
        $orders_query = Order::orderBy('id', 'ASC')->where('order_style_no', 'like', '%' . $q . '%');
        $floors_query = Floor::orderBy('id', 'ASC')->where('floor_no', 'like', '%' . $q . '%');
        $lines_query = Line::orderBy('id', 'ASC')->where('line_no', 'like', '%' . $q . '%');
        $operation_bulletins_query = OperationBulletin::orderBy('id', 'desc')
            ->orWhere('input_date', 'like', '%' . $q . '%')
            ->orWhere('prepared_date', 'like', '%' . $q . '%');
        if ($buyers_query->count() > 0) {
            $buyers = $buyers_query->get();
            foreach ($buyers as $buyer) {
                $operation_bulletins_query = $operation_bulletins_query->orWhere('buyer_id', $buyer->id);
            }
        }
        
        if ($orders_query->count() > 0) {
            $orders = $orders_query->get();
            foreach ($orders as $order) {
                $operation_bulletins_query = $operation_bulletins_query->orWhere('order_id', $order->id);
            }
        }
        if ($floors_query->count() > 0) {
            $floors = $floors_query->get();
            foreach ($floors as $floor) {
                $operation_bulletins_query = $operation_bulletins_query->orWhere('floor_id', $floor->id);
            }
        }
        if ($lines_query->count() > 0) {
            $lines = $lines_query->get();
            foreach ($lines as $line) {
                $operation_bulletins_query = $operation_bulletins_query->orWhere('line_id', $line->id);
            }
        }
        $operation_bulletins = $operation_bulletins_query->paginate();

        return view('iedroplets::pages.operation_bulletins', [
            'operation_bulletins' => $operation_bulletins,
            'q' => $q
        ]);
    }

    public function download($id)
    {
        try {
            $operation_bulletin = OperationBulletin::with('operationBulletinDetails')->findOrFail($id);
            $pdf = PDF::setOption('enable-local-file-access', true)
            ->loadView('iedroplets::pages.operation_bulletin_download', compact('operation_bulletin'))
            ->setPaper('A4')->setOrientation('landscape')->setOptions([
                'header-html' => view('skeleton::pdf.header'),
                'footer-html' => view('skeleton::pdf.footer'),
            ]);
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->back();
        }
        return $pdf->stream('operation-bulletin.pdf');
    }

    public function getChartData($operation_bulletin)
    {
        $operationBulletinDetails = $operation_bulletin->operationBulletinDetails ?? [];
        $chartTaskData = [];
        $chartTimeData = [];
        $chartTargetData = [];
        $specialMachines = [];
        $specialOperations = [];
        $guideFolderNo = 0;
        $totalTarget = 0;
        foreach ($operationBulletinDetails as $bulletindetail) {
            if ($bulletindetail->guide_or_folder_id) {
                ++$guideFolderNo;
            }

            if (($bulletindetail->special_machine == 1) &&
                !in_array($bulletindetail->machineType->name, $specialMachines)) {
                $specialMachines[] = $bulletindetail->machineType->name;
            }

            if (($bulletindetail->special_task == 1) &&
                !in_array($bulletindetail->task->name, $specialOperations)) {
                $specialOperations[] = $bulletindetail->task->name;
            }

            $chartTaskData[] = $bulletindetail->task->name ?? '';
            $chartTimeData[] = $bulletindetail->time ?? 0;
            $target = round(3600 / $bulletindetail->new_time);
            $chartTargetData[] = $target;
            $totalTarget += $target;
        }

        $chart_data = [
            'chartTaskData' => $chartTaskData,
            'chartTimeData' => $chartTimeData,
            'chartTargetData' => $chartTargetData,
        ];
        return $chart_data;
    }

    public function copyOperationBulletin($id)
    {
        $operation_bulletin = OperationBulletin::with('operationBulletinDetails')->findOrFail($id);

        $floors = Floor::orderBy('sort', 'asc')->pluck('floor_no', 'id')->all();
        $buyers = Buyer::orderBy('id', 'asc')->pluck('name', 'id')->all();
        $tasks = Task::orderBy('id', 'asc')->pluck('name', 'id')->all();
        $machine_types = MachineType::orderBy('id', 'asc')->pluck('name', 'id')->all();
        $operator_skills = OperatorSkill::orderBy('id', 'asc')->pluck('name', 'id')->all();
        $guide_or_folders = GuideOrFolder::orderBy('id', 'asc')->pluck('name', 'id')->all();

        return view('iedroplets::forms.operation_bulletin_copy', [
            'operation_bulletin' => $operation_bulletin,
            'floors' => $floors,
            'buyers' => $buyers,
            'tasks' => $tasks,
            'machine_types' => $machine_types,
            'operator_skills' => $operator_skills,
            'guide_or_folders' => $guide_or_folders
        ]);
    }

    public function copyOperationBulletinPost(OperationBulletinRequest $request)
    {
        try {

            DB::transaction(function () use ($request) {

                $operationBulletinData = array_filter($this->getOperationBulletinBasicData($request->all()));
                $operationBulletin = OperationBulletin::create($operationBulletinData);

                if ($operationBulletin && $request->hasFile('sketch')) {

                    $time = time();
                    $file = $request->sketch;
                    $file->storeAs('sketch_images', $time . $file->getClientOriginalName());
                    $operationBulletin->sketch = $time . $file->getClientOriginalName();
                    $operationBulletin->save();
                }

                $operationBulletinDetailsData = $this->getOperationBulletinDetailData($operationBulletin, $request->all());

                if (count($operationBulletinDetailsData)) {
                    OperationBulletinDetail::insert($operationBulletinDetailsData);
                }
            });

            Session::flash('success', S_SAVE_MSG);
        } Catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect('/operation-bulletins');
    }

}
