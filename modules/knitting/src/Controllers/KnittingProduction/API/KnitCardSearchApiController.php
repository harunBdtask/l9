<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\KnittingProduction\API;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Knitting\Filters\Filter;
use SkylarkSoft\GoRMG\Knitting\Models\KnitCard;
use SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram;
use Symfony\Component\HttpFoundation\Response;

class KnitCardSearchApiController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function __invoke(Request $request): JsonResponse
    {
        try {
            $knitCard = KnitCard::query()
                ->where('current_production_status', 2)
                ->when($request->get('production_from_date') && $request->get('production_to_date'), Filter::dateRangeFilter('knit_card_date', [
                    $request->get('production_from_date'), $request->get('production_to_date')
                ]))
                ->whereHas('program', function ($query) use ($request) {
                    $query->when($request->get('factory_id'), Filter::applyFilter('factory_id', $request->get('factory_id')));
                    $query->when($request->get('knitting_source_id'),
                        Filter::applyFilter('knitting_source_id', $request->get('knitting_source_id')));
                    $query->when($request->get('production_status'),
                        Filter::applyFilter('production_pending_status', $request->get('production_status')));
                    $query->when($request->get('program_no'),
                        Filter::applyFilter('program_no', $request->get('program_no')));
                    $query->when($request->get('program_from_date') && $request->get('program_to_date'),
                        Filter::dateRangeFilter('program_date', [
                            $request->get('program_from_date'), $request->get('program_to_date')
                        ])
                    );
                })
                ->with([
                    'factory:id,factory_name',
                    'program.knittingParty:id,factory_name',
                    'program.planInfo'
                ])
                ->orderBy('id', 'DESC')
                ->paginate(15);

            $data['current_page'] = $knitCard->currentPage();
            $data['last_page'] = $knitCard->lastPage();
            $data['total'] = $knitCard->total();

            $data['data'] = $knitCard->getCollection()->transform(function ($knitCard) {
                return [
                    'id' => $knitCard->id,
                    'factory_name' => $knitCard->factory->factory_name,
                    'knitting_party' => $knitCard->party_name,
                    'buyer_name' => $knitCard->program->planInfo->buyer_name,
                    'style_name' => $knitCard->program->planInfo->style_name,
                    'unique_id' => $knitCard->program->planInfo->unique_id,
                    'booking_no' => $knitCard->program->planInfo->booking_no,
                    'body_part' => $knitCard->program->planInfo->bodyPart->name,
                    'color_type' => $knitCard->program->planInfo->colorType->color_types,
                    'fabric_description' => $knitCard->program->planInfo->fabric_description,
                    'program_no' => $knitCard->program->program_no,
                    'requisition_no' => $knitCard->program->requisition_no,
                    'machine_nos' => $knitCard->program->machine_nos ? implode(', ', $knitCard->program->machine_nos) : null,
                    'program_qty' => $knitCard->program->program_qty,
                ];
            });

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
