<?php


namespace SkylarkSoft\GoRMG\Inventory\Controllers\API\Stores;


use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use SkylarkSoft\GoRMG\Inventory\Models\Store;

class StoreController extends Controller
{
    public function index()
    {
        $company = request('company');
        $location = request('location');
        $store = request('store');
        $floor = request('floor');
        $room = request('room');
        $rack = request('rack');
        $shelf = request('shelf');
        $bin = request('bin');

        $stores = Store::query()
            ->with([
                'factory',
//                'floors.rooms.racks.shelves.bins',
                'floors' => function ($query) use ($floor) {
                    $query->where('name', 'like', '%' . $floor . '%');
                },
                'floors.rooms' => function ($query) use ($room) {
                    $query->where('name', 'like', '%' . $room . '%');
                },
                'floors.rooms.racks' => function ($query) use ($rack) {
                    $query->where('name', 'like', '%' . $rack . '%');
                },
                'floors.rooms.racks.shelves' => function ($query) use ($shelf) {
                    $query->where('name', 'like', '%' . $shelf . '%');
                },
                'floors.rooms.racks.shelves.bins' => function ($query) use ($bin) {
                    $query->where('name', 'like', '%' . $bin . '%');
                }
            ])
            ->when($company, function (Builder $query) use ($company) {
                return $query->whereHas('factory', function (Builder $query) use ($company) {
                    return $query->where('group_name', 'LIKE', '%' . $company . '%');
                });
            })
            ->when($location, function (Builder $query) use ($location) {
                return $query->whereHas('factory', function (Builder $query) use ($location) {
                    return $query->where('factory_address', 'LIKE', '%' . $location . '%');
                });
            })
            ->when($store, function (Builder $query) use ($store) {
                return $query->where('name', 'LIKE', '%' . $store . '%');
            })
            ->orderBy('id', 'desc')
//            ->limit(5)
            ->get();

        return response()->json($stores);
    }
}
