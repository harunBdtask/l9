<?php

namespace SkylarkSoft\GoRMG\Planing\Controllers\CapacityPlaning;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\Planing\Models\ContainerProfile\ContainerSummaries;
use SkylarkSoft\GoRMG\Planing\Requests\ContainerSummariesFormRequest;
use Symfony\Component\HttpFoundation\Response;

class ContainerSummariesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $containerSummaries = ContainerSummaries::query()
            ->search($search)
            ->with('containerProfile')
            ->orderBy('id', 'desc')
            ->paginate();

        return view('planing::container_profile.container_fill_up.index', [
            'containerSummaries' => $containerSummaries,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('planing::container_profile.container_fill_up.form');
    }

    public function store(ContainerSummariesFormRequest $request): JsonResponse
    {
        try {
            foreach ($request->all() as $container) {
                if (count($container['items']) === 0) {
                    $containerSummaries = ContainerSummaries::query()->find($container['id']);
                    if (isset($containerSummaries)) {
                        $containerSummaries->delete();
                    }

                    continue;
                }

                $container['po_list'] = $container['items'];
                $containerSummaries = ContainerSummaries::query()->updateOrCreate([
                    'id' => $container['id'] ?? null,
                ], $container);
                $containerSummaries->fill($container)->save();
            }

            return response()->json([
                'message' => 'Container summaries stored successfully',
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
