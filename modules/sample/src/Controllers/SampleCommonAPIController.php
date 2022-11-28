<?php

namespace SkylarkSoft\GoRMG\Sample\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Models\Budget;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\QuotationInquiry;
use SkylarkSoft\GoRMG\Sample\Models\SampleOrderRequisition;
use SkylarkSoft\GoRMG\Sample\Models\SampleProcessing;
use SkylarkSoft\GoRMG\Sample\Models\SampleTemplate;
use SkylarkSoft\GoRMG\Sample\Models\SampleTNA;
use SkylarkSoft\GoRMG\Sample\Models\SampleTrimsIssue;
use SkylarkSoft\GoRMG\Sample\Models\SampleTrimsReceive;
use SkylarkSoft\GoRMG\Sample\Services\SampleRequisition\SampleOrderRequisitionService;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\BuyingAgentMerchantModel;
use SkylarkSoft\GoRMG\SystemSettings\Models\Color;
use SkylarkSoft\GoRMG\SystemSettings\Models\FabricNature;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsItem;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample;
use SkylarkSoft\GoRMG\SystemSettings\Models\Size;
use SkylarkSoft\GoRMG\SystemSettings\Models\Team;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Symfony\Component\HttpFoundation\Response;

class SampleCommonAPIController extends Controller
{
    public function main(SampleOrderRequisition $sampleOrderRequisition): JsonResponse
    {
        return response()->json($sampleOrderRequisition);
    }

    public function details(SampleOrderRequisition $sampleOrderRequisition): JsonResponse
    {
        $details = $sampleOrderRequisition->details()->get();

        return response()->json($details);
    }

    public function fabrics(SampleOrderRequisition $sampleOrderRequisition): JsonResponse
    {
        $fabrics = $sampleOrderRequisition->fabrics()->first();

        return response()->json($fabrics);
    }

    public function fabricDetails(SampleOrderRequisition $sampleOrderRequisition): JsonResponse
    {
        $fabrics = $sampleOrderRequisition->fabricDetails()->get();

        return response()->json($fabrics);
    }

    public function accessories(SampleOrderRequisition $sampleOrderRequisition): JsonResponse
    {
        $res = $sampleOrderRequisition->accessories()->get();

        return response()->json($res);
    }

    public function repeatStylesSearch()
    {
        try {
            $factoryId = request('factory_id');
            $buyerId = request('buyer_id');
            $styleName = request('style_name');
            $samples = SampleOrderRequisition::with(['factory:id,factory_name', 'buyer:id,name'])
                ->when($factoryId, function ($query) use ($factoryId) {
                    $query->where('factory_id', $factoryId);
                })
                ->when($buyerId, function ($query) use ($buyerId) {
                    $query->where('buyer_id', $buyerId);
                })
                ->when($styleName, function ($query) use ($styleName) {
                    $query->where('style_name', $styleName);
                })
                ->get();

            $processData = collect($samples)->map(function ($value) {
                $repeatedOrdersCount = SampleOrderRequisition::query()
                    ->where('repeat_style_name', $value->style_name)
                    ->count();
                $repeatedStyle = str_pad((string) ($repeatedOrdersCount + 1), 2, '0', STR_PAD_LEFT);

                return [
                    'id' => null,
                    'requisition_id' => $value->requisition_id,
                    'sample_stage' => $value->sample_stage,
                    'req_date' => $value->req_date,
                    'style_name' => $value->style_name. '-' . $repeatedStyle,
                    'repeat_style_name' => $value->style_name,
                    'factory_id' => $value->factory_id,
                    'location' => $value->location,
                    'buyer_id' => $value->buyer_id,
                    'season_id' => $value->season_id,
                    'team_leader_id' => $value->team_leader_id,
                    'dealing_merchant_id' => $value->dealing_merchant_id,
                    'bh_merchant_id' => $value->bh_merchant_id,
                    'product_department_id' => $value->product_department_id,
                    'buyer_ref' => $value->buyer_ref,
                    'agent_name' => $value->agent_name,
                    'est_ship_date' => $value->est_ship_date,
                    'delivery_date' => $value->delivery_date,
                    'remarks' => $value->remarks,
                    'currency' => $value->currency,
                    'ready_for_approve' => $value->ready_for_approve,
                    'lab_test' => $value->lab_test,
                    'booking_no' => $value->booking_no,
                    'control_ref_no' => $value->control_ref_no,
                    'ref_no' => $value->ref_no,
                    'factory' => $value->factory,
                    'buyer' => $value->buyer,
                ];
            });

            return response()->json($processData);
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function stylesSearch()
    {
        try {
            DB::beginTransaction();
            $factoryId = request('factory_id');
            $buyerId = request('buyer_id');
            $year = request('year');
            $sampleStage = request('sample_stage');
            $inquiryId = request('inquiry_id');
            $styleName = request('style_name');
            $uniqId = request('uniq_id');
            $startDate = request('start_date');
            $endDate = request('end_date');

            if ($sampleStage == SampleOrderRequisition::AFTER_ORDER) {
                $orders = Order::with('factory', 'buyer')
                    ->when($factoryId, function ($query) use ($factoryId) {
                        $query->where('factory_id', $factoryId);
                    })
                    ->when($buyerId, function ($query) use ($buyerId) {
                        $query->where('buyer_id', $buyerId);
                    })
                    ->when($year, function ($query) use ($year) {
                        $query->whereYear('created_at', $year);
                    })
                    ->when($uniqId, function ($query) use ($uniqId) {
                        $query->where('job_no', $uniqId);
                    })
                    ->when($styleName, function ($query) use ($styleName) {
                        $query->where('style_name', $styleName);
                    })->get();

                return response()->json(['data' => $orders]);
            }

            if (in_array($sampleStage, [SampleOrderRequisition::BEFORE_ORDER, SampleOrderRequisition::RND])) {
                $quotationInquiries = QuotationInquiry::with('factory', 'buyer')
                    ->when($factoryId, function ($query) use ($factoryId) {
                        $query->where('factory_id', $factoryId);
                    })
                    ->when($buyerId, function ($query) use ($buyerId) {
                        $query->where('buyer_id', $buyerId);
                    })
                    ->when($year, function ($query) use ($year) {
                        $query->whereYear('created_at', $year);
                    })
                    ->when($styleName, function ($query) use ($styleName) {
                        $query->where('style_name', $styleName);
                    })
                    ->when($inquiryId, function ($query) use ($inquiryId) {
                        $query->where('quotation_id', $inquiryId);
                    })
                    ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                        $query->whereBetween('inquiry_date', [$startDate, $endDate]);
                    })->get();

                return response()->json(['data' => $quotationInquiries]);
            }
            DB::commit();

            return response()->json(\request()->all());
        } catch (\Throwable $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function qtyFormData(): JsonResponse
    {
        $styleName = request('style_name');
        $samples = GarmentsSample::where('status', 'active')->get(['id', 'name as text']);
        $colors = Color::where('status', 1)->get(['id', 'name as text']);

        if ($styleName) {
            $items = (new SampleOrderRequisitionService())->garmentItemsByStyleName($styleName);

            return response()->json(compact('items', 'samples', 'colors'));
        }

        $items = GarmentsItem::all(['id', 'name as text']);

        return response()->json(compact('items', 'samples', 'colors'));
    }

    public function gmtsSizes(): JsonResponse
    {
        try {
            $data = Size::query()->get();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fabricNatures(): JsonResponse
    {
        try {
            $data = FabricNature::all();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function users(): JsonResponse
    {
        try {
            $data = User::all();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function buyingAgentMerchants(): JsonResponse
    {
        try {
            $data = BuyingAgentMerchantModel::all();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function userTeamInfo()
    {
        try {
            $data['dealing_merchant_id'] = $user = Team::query()->where('member_id', auth()->user()->id)->where('role', 'Member')->first();
            if (isset($user->team_name)) {
                $data['team_leader_id'] = Team::query()->where('team_name', $user->team_name)->where('role', 'Leader')->first();
            }
            $data['login_user'] = auth()->user()->id;

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function fabricCostingFromBudget()
    {
        try {
            $styleName = request('style_name');
            $budget = Budget::query()->with('fabricCosting')->where('style_name', $styleName)->first();
            if (! empty($budget)) {
                $fabricCosting = $budget->fabricCosting['details']['details'];
                $fabInfo = collect($fabricCosting['fabricForm'])->first();
                $fabricMain = [
                    'id' => null,
                    'fabric_nature_id' => $fabInfo['fabric_nature_id'] ?? null,
                    'fabric_source_id' => $fabInfo['fabric_source'] ?? null,
                    'supplier_id' => $fabInfo['supplier_id'] ?? null,
                    'delivery_id' => null,
                    'delivery_date' => null,
                ];
                $formatFabricsCosting = collect($fabricCosting['fabricForm'])->map(function ($collection) {
                    $greyConsDetails = $collection['greyConsForm']['details'];

                    return [
                        'body_part_id' => $collection['body_part_id'] ?? null,
                        'details' => [
                            'id' => null,
                            'gmts_color_id' => $greyConsDetails['color_id'] ?? null,
                            'combo_contrast_color' => $greyConsDetails['color_id'] ?? null,
                            'pantone_no' => null,
                            'labdip' => null,
                            'color_type_id' => $collection['color_type_id'] ?? null,
                            'construction' => null,
                            'fabric_description' => $collection['fabric_composition_id'] ?? null,
                            'dia_type' => $collection['dia_type'] ?? null,
                            'uom_id' => $collection['uom'] ?? null,
                            'remarks' => null,
                        ],
                        'calculations' => [
                            'store_available_qty' => null,
                            'finish_dia' => $greyConsDetails['dia'] ?? null,
                            'gsm' => $collection['gsm'] ?? null,
                            'finish_qty' => $greyConsDetails['finish_cons'] ?? null,
                            'process_loss' => $greyConsDetails['process_loss'] ?? null,
                            'grey_qty' => $greyConsDetails['grey_cons'] ?? null,
                            'total_req_qty' => $greyConsDetails['total_qty'] ?? null,
                            'rate' => $greyConsDetails['rate_avg'] ?? null,
                            'total_amount' => $greyConsDetails['total_amount'] ?? null,
                        ],
                    ];
                });

                return response()->json(['fabricMain' => $fabricMain, 'fabricItems' => $formatFabricsCosting], Response::HTTP_OK);
            } else {
                return response()->json("No Data Found", Response::HTTP_NO_CONTENT);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function trimCostingFromBudget()
    {
        try {
            $styleName = request('style_name');
            $budget = Budget::query()->with('trimCosting')->where('style_name', $styleName)->first();
            if (! empty($budget)) {
                $trimCosting = $budget->trimCosting['details']['details'];
                $formatTrimCosting = collect($trimCosting)->map(function ($collection) {
                    $breakdownDetails = $collection['breakdown']['details'];

                    return [
                        'id' => null,
                        'item_group_id' => $collection['group_id'] ?? null,
                        'details' => [
                            'description' => $collection['description'] ?? null,
                            'supplier_id' => $collection['nominated_supplier_id'] ?? null,
                            'gmts_color_id' => $breakdownDetails['color_id'] ?? null,
                            'color' => null,
                            'size_id' => $breakdownDetails['size_id'] ?? null,
                            'item_group_uom_id' => $collection['cons_uom_id'] ?? null,
                            'delivery_id' => null,
                            'delivery_date' => null,
                            'remarks' => $collection['remarks'] ?? null,
                            'image_path' => null,
                        ],
                        'calculations' => [
                            'req_qty' => $collection['total_quantity'] ?? null,
                            'rate' => $collection['rate'] ?? null,
                            'total_amount' => $collection['total_amount'] ?? null,
                        ],
                    ];
                });

                return response()->json($formatTrimCosting, Response::HTTP_OK);
            } else {
                return response()->json("No Data Found", Response::HTTP_NO_CONTENT);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function buyers(): JsonResponse
    {
        try {
            $data = Buyer::all();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function samples(): JsonResponse
    {
        try {
            $data = SampleOrderRequisition::all();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function sample(): JsonResponse
    {
        try {
            $request = request()->all() ?? null;
            $buyer_id = $request['buyer_id'] ?? null;
            $style_name = $request['style_name'] ?? null;
            $data = SampleOrderRequisition::query()
            ->when($buyer_id && ! $style_name, function ($query) use ($buyer_id) {
                $query->where('buyer_id', $buyer_id);
            })
            ->when($style_name && $buyer_id, function ($query) use ($style_name, $buyer_id) {
                $query->where('style_name', $style_name);
                $query->where('buyer_id', $buyer_id);
            })
            ->get();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function tna(SampleTNA $sampleTNA): JsonResponse
    {
        return response()->json($sampleTNA);
    }

    public function tnaTemplates(): JsonResponse
    {
        try {
            $data = SampleTemplate::where('type', 'sample_tna')->get();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function processFromTNATemplate(SampleTemplate $sampleTemplate)
    {
        try {
            $processData = [];

            return response()->json($sampleTemplate,  Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function processFromSample(SampleOrderRequisition $sampleOrderRequisition)
    {
        try {
            $fabricDetails = $sampleOrderRequisition->fabricDetails()->get();
            $processData = collect($sampleOrderRequisition->details)->map(function ($value, $key) use ($fabricDetails) {
                return [
                    'id' => null,
                    'sample_processing_id' => null,
                    'sample_order_requisition_id' => $value->sample_order_requisition_id,
                    'sample_id' => $value->sample_id,
                    'gmts_item_id' => $value->gmts_item_id,
                    'details' => $value->details,
                    'calculations' => $value->calculations,
                    'fabric_details' => [
                        'combo_contrast_color' => $fabricDetails[$key]->details['combo_contrast_color'],
                        'body_part_id' => $fabricDetails[$key]->body_part_id,
                        'fabric_description' => $fabricDetails[$key]->details['fabric_description'],
                    ],
                ];
            });

            return response()->json($processData,  Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function processing(SampleProcessing $sampleProcessing): JsonResponse
    {
        return response()->json($sampleProcessing);
    }

    public function processingDetails(SampleProcessing $sampleProcessing): JsonResponse
    {
        $details = $sampleProcessing->processingDetails()->get();

        return response()->json($details);
    }

    public function production(SampleProcessing $sampleProcessing): JsonResponse
    {
        return response()->json($sampleProcessing->productions()->first());
    }

    public function productionDetails(SampleProcessing $sampleProcessing): JsonResponse
    {
        $details = $sampleProcessing->sampleProductionDetails()->get();

        return response()->json($details);
    }

    public function issueBasis(): JsonResponse
    {
        $data = SampleTrimsIssue::ISSUE_BASIS;

        return response()->json($data);
    }

    public function processFromSampleAccessories(SampleOrderRequisition $sampleOrderRequisition): JsonResponse
    {
        $processData = collect($sampleOrderRequisition->accessories)->map(function ($value) {
            return [
                'id' => null,
                'item_group_id' => $value->item_group_id,
                'supplier_id' => $value->details['supplier_id'],
                'size_id' => $value->details['size_id'],
                'item_group_uom_id' => $value->details['item_group_uom_id'],
                'details' => [
                    'description' => $value->details['description'],
                    'color' => $value->details['color'],
                    'remarks' => $value->details['remarks'],
                    'image_path' => $value->details['image_path'],
                ],
                'calculations' => [
                    'req_qty' => $value->calculations['req_qty'],
                    'avail_stock_qty' => null,
                    'prv_issue_qty' => null,
                    'issue_qty' => null,
                    'issue_return_qty' => null,
                    'transfer_qty' => null,
                ],
            ];
        });

        return response()->json($processData);
    }

    public function trimsIssue(SampleTrimsIssue $sampleTrimsIssue): JsonResponse
    {
        return response()->json($sampleTrimsIssue);
    }

    public function trimsIssueDetails(SampleTrimsIssue $sampleTrimsIssue): JsonResponse
    {
        $details = $sampleTrimsIssue->trimsIssueDetails()->get();

        return response()->json($details);
    }

    public function trimsIssues(): JsonResponse
    {
        try {
            $data = SampleTrimsIssue::all();

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function processFromSmpTrimsIssue(SampleTrimsIssue $sampleTrimsIssue): JsonResponse
    {
        $processData = collect($sampleTrimsIssue->trimsIssueDetails)->map(function ($value) {
            return [
                'id' => null,
                'item_group_id' => $value->item_group_id,
                'supplier_id' => $value->supplier_id,
                'size_id' => $value->size_id,
                'item_group_uom_id' => $value->item_group_uom_id,
                'details' => [
                    'description' => $value->details['description'],
                    'color' => $value->details['color'],
                    'remarks' => $value->details['remarks'],
                    'image_path' => $value->details['image_path'],
                ],
                'calculations' => [
                    'req_qty' => $value->calculations['req_qty'],
                    'rcv_qty' => $value->calculations['req_qty'],
                ],
            ];
        });

        return response()->json($processData);
    }

    public function trimsReceive(SampleTrimsReceive $sampleTrimsReceive): JsonResponse
    {
        return response()->json($sampleTrimsReceive);
    }

    public function trimsReceiveDetails(SampleTrimsReceive $sampleTrimsReceive): JsonResponse
    {
        $details = $sampleTrimsReceive->trimsReceiveDetails()->get();

        return response()->json($details);
    }
}
