<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Season;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\Merchandising\Models\QuotationInquiry;
use SkylarkSoft\GoRMG\SystemSettings\Requests\SeasonApiRequest;
use SkylarkSoft\GoRMG\SystemSettings\Requests\SeasonRequest;

class SeasonController extends Controller
{
    public function index(Request $request)
    {
        $searchKey = $request->get('q');
        $factories = Factory::query()->userWiseFactories()->get();
        $buyers = Buyer::all();
        $years = $this->years();
        $seasons = Season::query()
            ->when($searchKey, function ($query) use ($searchKey) {
                $query->where('season_name', 'LIKE', "%$searchKey%");
            })
            ->groupBy('factory_id', 'buyer_id')
            ->get()
            ->map(function ($season) use ($searchKey) {
                $data = Season::with('factory', 'buyer')
                    ->when($searchKey, function ($query) use ($searchKey) {
                        $query->where('season_name', 'LIKE', "%$searchKey%");
                    })
                    ->where('factory_id', $season->factory_id)
                    ->where('buyer_id', $season->buyer_id)
                    ->first();
                $data['details'] = Season::query()
                    ->when($searchKey, function ($query) use ($searchKey) {
                        $query->where('season_name', 'LIKE', "%$searchKey%");
                    })
                    ->where('factory_id', $season->factory_id)
                    ->where('buyer_id', $season->buyer_id)
                    ->get()
                    ->map(function ($season) {
                        return [
                            'season' => $season->season_name,
                            'year_from' => $season->year_from,
                            'year_to' => $season->year_to,
                        ];
                    });

                return $data;
            });

        return view('system-settings::pages.seasons', compact('factories', 'buyers', 'years', 'seasons'));
    }

    protected function seasonCreateFactory($request)
    {
        $seasonUnique = array_unique($request->get('season_name'));
        $seasonClean = array_filter($seasonUnique, 'strlen');
        foreach ($seasonClean as $key => $season) {
            $data['buyer_id'] = $request->get('buyer_id');
            $data['factory_id'] = $request->get('factory_id');
            $data['season_name'] = $request->get('season_name')[$key];
            $data['year_from'] = $request->get('year_from')[$key];
            $data['year_to'] = $request->get('year_to')[$key];
            Season::query()->create($data);
        }
    }

    public function store(SeasonRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->seasonCreateFactory($request);
            Session::flash('success', 'Data Created Successfully');
            DB::commit();
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('seasons');
    }

    public function saveSeason(SeasonApiRequest $request)
    {
        try {
            $data['factory_id'] = $request->get('factory_id');
            $data['buyer_id'] = $request->get('buyer_id');
            $data['season_name'] = $request->get('season_name');
            $data['year_from'] = $request->get('year_from');
            $data['year_to'] = $request->get('year_to');
            Season::query()->create($data);
            return response()->json($data, 200);
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }

    public function edit($factoryId, $buyerId)
    {
        $factories = Factory::query()->userWiseFactories()->get();
        $buyers = Buyer::all();
        $years = $this->years();
        $seasonIds = Season::query()
            ->where('factory_id', $factoryId)
            ->where('buyer_id', $buyerId)
            ->pluck('id')
            ->toArray();

        $seasonIdQI = QuotationInquiry::query()
            ->where([
                'factory_id' => $factoryId,
                'buyer_id' => $buyerId,
            ])
            ->whereIn('season_id', $seasonIds)
            ->get();

        $seasonIdPQ = PriceQuotation::query()->where([
            'factory_id' => $factoryId,
            'buyer_id' => $buyerId,
        ])->whereIn('season_id', $seasonIds)->get();

        $seasonIdOrderEntry = Order::query()->where([
            'factory_id' => $factoryId,
            'buyer_id' => $buyerId,
        ])->whereIn('season_id', $seasonIds)->get();

        if (!(count($seasonIdQI) > 0 || count($seasonIdPQ) > 0 || count($seasonIdOrderEntry) > 0)) {
            $season = Season::where('factory_id', $factoryId)->where('buyer_id', $buyerId)->get()
                ->map(function ($season) {
                    return [
                        'id' => $season->id,
                        'season' => $season->season_name,
                        'year_from' => $season->year_from,
                        'year_to' => $season->year_to,
                    ];
                });
            return view('system-settings::render.season', compact('factoryId', 'buyerId', 'season', 'factories', 'buyers', 'years'))->render();
        } else {
            return response()->json([
                'message' => 'Can Not be Edited ! It is currently associated with Others',
                'status' => 'error',
            ]);
        }
    }

    public function editSeason($factoryId, $buyerId)
    {
        $season = Season::with('factory:id,factory_name', 'buyer:id,name')->where('factory_id', $factoryId)->where('buyer_id', $buyerId)->get();
        $factories = Factory::query()->userWiseFactories()->pluck('factory_name', 'id');
        $buyers = Buyer::query()->pluck('name', 'id');
        $years = $this->years();

        return view('system-settings::forms.season', compact('season', 'factories', 'buyers', 'years'));
    }

    public function update($factoryId, $buyerId, SeasonRequest $request)
    {
        DB::beginTransaction();
        Season::query()
            ->where('factory_id', $factoryId)
            ->where('buyer_id', $buyerId)
            ->delete();
        $request->merge([
            'buyer_id' => $buyerId,
            'factory_id' => $factoryId
        ]);
        $this->seasonCreateFactory($request);
        DB::commit();
        return redirect('seasons');
    }

    private function years(): array
    {
        $years = [];
        for ($i = 1; $i < 35; $i++) {
            $year = 2020 + $i;
            array_push($years, $year);
        }

        return $years;
    }
}
