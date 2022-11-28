<?php

namespace SkylarkSoft\GoRMG\Knitting\Controllers\PlanningInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Knitting\Models\PlanningInfo;

class SearchEntityController extends Controller
{
    public function buyerSearch(Request $request)
    {
        try {
            $search = $request->search ?? null;
            $data = PlanningInfo::query()
                ->select('buyer_name')
                ->when($search, function ($q) use ($search) {
                    return $q->where('buyer_name', $search);
                })
                ->groupBy('buyer_name')
                ->get()
                ->map(function ($item, $key) {
                    $data['id'] = $item->buyer_name;
                    $data['text'] = $item->buyer_name;
                    return $data;
                });
            return [
                'data' => $data,
                'errors' => null,
                'message' => 'Success',
                'status' => 200,
            ];
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'status' => 500,
                'errors' => $e->getMessage(),
            ]);
        }
    }

    public function uniqueIdSearch(Request $request)
    {
        try {
            $search = $request->search ?? null;
            $data = PlanningInfo::query()
                ->select('unique_id')
                ->when($search, function ($q) use ($search) {
                    return $q->where('unique_id', $search);
                })
                ->groupBy('unique_id')
                ->get()
                ->map(function ($item, $key) {
                    $data['id'] = $item->unique_id;
                    $data['text'] = $item->unique_id;
                    return $data;
                });
            return [
                'data' => $data,
                'errors' => null,
                'message' => 'Success',
                'status' => 200,
            ];
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'status' => 500,
                'errors' => $e->getMessage(),
            ]);
        }
    }

    public function styleSearch(Request $request)
    {
        try {
            $search = $request->search ?? null;
            $data = PlanningInfo::query()
                ->select('style_name')
                ->when($search, function ($q) use ($search) {
                    return $q->where('style_name', $search);
                })
                ->groupBy('style_name')
                ->get()
                ->map(function ($item, $key) {
                    $data['id'] = $item->style_name;
                    $data['text'] = $item->style_name;
                    return $data;
                });
            return [
                'data' => $data,
                'errors' => null,
                'message' => 'Success',
                'status' => 200,
            ];
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'status' => 500,
                'errors' => $e->getMessage(),
            ]);
        }
    }

    public function poSearch(Request $request)
    {
        try {
            $search = $request->search ?? null;
            $data = PlanningInfo::query()
                ->select('po_no')
                ->when($search, function ($q) use ($search) {
                    return $q->where('po_no', $search);
                })
                ->groupBy('po_no')
                ->get()
                ->map(function ($item, $key) {
                    $data['id'] = $item->po_no;
                    $data['text'] = $item->po_no;
                    return $data;
                });
            return [
                'data' => $data,
                'errors' => null,
                'message' => 'Success',
                'status' => 200,
            ];
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'status' => 500,
                'errors' => $e->getMessage(),
            ]);
        }
    }

    public function bookingNoSearch(Request $request)
    {
        try {
            $search = $request->search ?? null;
            $data = PlanningInfo::query()
                ->select('booking_no')
                ->when($search, function ($q) use ($search) {
                    return $q->where('booking_no', $search);
                })
                ->groupBy('booking_no')
                ->get()
                ->map(function ($item, $key) {
                    $data['id'] = $item->booking_no;
                    $data['text'] = $item->booking_no;
                    return $data;
                });
            return [
                'data' => $data,
                'errors' => null,
                'message' => 'Success',
                'status' => 200,
            ];
        } catch (\Exception $e) {
            return response()->json([
                'data' => [],
                'status' => 500,
                'errors' => $e->getMessage(),
            ]);
        }
    }
}
