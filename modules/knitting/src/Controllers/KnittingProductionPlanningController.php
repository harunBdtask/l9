<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use SkylarkSoft\GoRMG\Knitting\Models\KnitCard;
use SkylarkSoft\GoRMG\Knitting\Models\KnitCardMachineDetail;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class KnittingProductionPlanningController
{
    public function getKnitCard(Request $request): JsonResponse
    {
        $dia = $request->get('dia') == null || $request->get('dia') == 'null' ? null : $request->get('dia');
        $date = $request->get('date') == null || $request->get('date') == 'null' ? null : $request->get('date');
        $planning = $request->get('planning');
        $type = $request->get('booking_type');
        $factoryId = $request->get('factory_id') ?? factoryId();
        $machineId = $request->get('machine_id') == null || $request->get('machine_id') == 'null' ? null : $request->get('machine_id');
        $machineType = $request->get('machine_type') == null || $request->get('machine_type') == 'null' ? null : $request->get('machine_type');

        $knitCard = KnitCard::query()
            ->with(['planInfo', 'program', 'machine.knittingFloor'])
            ->where('factory_id', $factoryId)
            ->when($dia, Filter::applyFilter('program_dia', $dia))
            ->when($date, Filter::applyFilter('knit_card_date', $date))
            ->when($machineId, Filter::applyFilter('current_machine_id', $machineId))
            ->when($planning == 'floor', Filter::applyFilter('machine_allocation_status', KnitCard::MACHINE_ALLOCATED))
            ->when($machineType, function ($query) use ($machineType) {
                return $query->whereHas('machine', function ($query) use ($machineType) {
                    return $query->where('machine_type_info', $machineType);
                });
            })
            ->when($type, function ($query) use ($type) {
                $query->whereHas('planInfo', function ($query) use ($type) {
                    $query->where('booking_type', $type);
                });
            })
            ->orderByDesc('created_at')
            ->paginate();

        $data['total'] = $knitCard->total();
        $data['last_page'] = $knitCard->lastPage();
        $data['current_page'] = $knitCard->currentPage();
        $data['data'] = $knitCard->getCollection()->transform(function ($knitCard) {
            return $this->format($knitCard);
        });

        return response()->json($data);
    }

    /**
     * @throws Throwable
     */
    public function assignMachine(Request $request): JsonResponse
    {
        try {
            $id = $request->get('id');
            $knitCard = KnitCard::query()
                ->with(['planInfo', 'program', 'machine.knittingFloor'])
                ->find($id);

            $prodStatus = array_flip(KnitCard::PROD_STATUS);
            $machineStatus = array_flip(KnitCard::MACHINE_STATUS);
            $preProdStatus = $knitCard->current_production_status;

            $reqMachineStatus = $request->get('machine_allocation_status');
            $reqProdStatus = $request->get('current_production_status');

            DB::beginTransaction();
            $updateStatus = $knitCard->toArray();
            $updateStatus['current_production_remarks'] = $request->get('production_remarks');

            /* Can't unallocated machine status when Production  status is Running Or Completed */
            if ($preProdStatus != $prodStatus['Running'] && $preProdStatus != $prodStatus['Completed']) {
                $updateStatus['current_machine_id'] = $request->get('current_machine_id');
                $updateStatus['machine_allocation_status'] = $reqMachineStatus;
                $updateStatus['current_machine_priority'] = $request->get('current_machine_priority');
            }

            /* When machine is allocated and Production N/A then Production status change to On Queue */
            if ($updateStatus['machine_allocation_status'] == $machineStatus['Allocated'] && $updateStatus['current_production_status'] == $prodStatus['N/A']) {
                $updateStatus['current_production_status'] = $prodStatus['On Queue'];
            }

            /* When Production Status on queue and Requested machine status Unallocated then Production status change to N/A */
            if ($updateStatus['current_production_status'] == $prodStatus['On Queue'] && $reqMachineStatus == $machineStatus['Unallocated']) {
                $updateStatus['current_production_status'] = $prodStatus['N/A'];
            }

            $knitCard->update($updateStatus);
            $this->storeMachineDetails($knitCard->toArray());

            DB::commit();
            return response()->json($this->format($knitCard), Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @throws Throwable
     */
    public function changeProductionStatus(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $id = $request->get('id');
            $knitCard = KnitCard::query()
                ->with(['planInfo', 'program', 'machine.knittingFloor'])
                ->find($id);

            $requestedProductionStatus = $request->get('current_production_status');
            $updateStatus['current_production_status'] = $requestedProductionStatus;
            $updateStatus['current_production_remarks'] = $request->get('production_remarks');

            throw_if($knitCard->machine_allocation_status == 0, 'Machine Allocate First');

            $prodStatus = array_flip(KnitCard::PROD_STATUS);
            $machineStatus = array_flip(KnitCard::MACHINE_STATUS);

            if ($requestedProductionStatus == $prodStatus['N/A']) {
                $updateStatus['machine_allocation_status'] = $machineStatus['Unallocated'];
            }

            $knitCard->update($updateStatus);
            $this->storeMachineDetails($knitCard->toArray());

            DB::commit();
            return response()->json($this->format($knitCard), Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function storeMachineDetails($knitCard)
    {
        $knitCard['knit_card_id'] = $knitCard['id'];
        $knitCard['machine_id'] = $knitCard['current_machine_id'];
        $knitCard['priority'] = $knitCard['current_machine_priority'];
        $knitCard['production_remarks'] = $knitCard['current_production_remarks'];

        KnitCardMachineDetail::query()->create($knitCard);
    }

    public function format($knitCard): array
    {
        return [
            'id' => $knitCard->id,
            'buyer_name' => $knitCard->planInfo->buyer_name,
            'style_name' => $knitCard->planInfo->style_name,
            'sales_order_no' => $knitCard->sales_order_no,
            'fabric_description' => $knitCard->planInfo->fabric_description,
            'program_no' => $knitCard->program->program_no,
            'booking_type' => $knitCard->planInfo->booking_type,
            'booking_no' => $knitCard->planInfo->booking_no,
            'program_dia' => $knitCard->program_dia,
            'program_gg' => $knitCard->program_gg,
            'knit_card_no' => $knitCard->knit_card_no,
            'assign_qty' => $knitCard->assign_qty,
            'production_target_qty' => $knitCard->production_target_qty,
            'balance_qty' => (int)$knitCard->assign_qty - (int)$knitCard->production_target_qty,
            'previous_machine_id' => $knitCard->current_machine_id,
            'current_machine_id' => $knitCard->current_machine_id,
            'machine_no' => optional($knitCard->machine)->machine_no,
            'machine_dia' => optional($knitCard->machine)->machine_dia,
            'machine_floor' => optional($knitCard->machine->knittingFloor)->name,
            'current_machine_priority' => $knitCard->current_machine_priority,
            'machine_allocation_status' => $knitCard->machine_allocation_status,
            'previous_production_status' => $knitCard->current_production_status,
            'current_production_status' => $knitCard->current_production_status,
            'allocation_status' => KnitCard::MACHINE_STATUS[$knitCard->machine_allocation_status],
            'production_status' => KnitCard::PROD_STATUS[$knitCard->current_production_status],
            'production_remarks' => $knitCard->current_production_remarks,
        ];
    }
}
