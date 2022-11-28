<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\CuttingTable;
use SkylarkSoft\GoRMG\SystemSettings\Requests\CuttingTableRequest;

class CuttingTableController extends Controller
{
    public function index()
    {
        $tables = CuttingTable::orderBy('table_no', 'asc')->paginate();

        return view('system-settings::cuttingdroplets.cutting_tables', ['tables' => $tables]);
    }

    public function create()
    {
        $floors = CuttingFloor::pluck('floor_no', 'id')->all();

        return view('system-settings::cuttingdroplets.cutting_table', ['table' => null, 'floors' => $floors]);
    }

    public function store(CuttingTableRequest $request)
    {
        $input = [
           'cutting_floor_id' => $request->cutting_floor_id,
           'table_no' => $request->table_no,
        ];

        CuttingTable::create($input);

        return redirect('/cutting-tables');
    }

    public function edit($id)
    {
        $floors = CuttingFloor::pluck('floor_no', 'id')->all();
        $table = CuttingTable::findOrFail($id);

        return view('system-settings::cuttingdroplets.cutting_table', ['table' => $table, 'floors' => $floors ]);
    }

    public function update($id, CuttingTableRequest $request)
    {
        $table = CuttingTable::findOrFail($id);
        $table->update($request->all());

        return redirect('/cutting-tables');
    }

    public function destroy($id)
    {
        $table = CuttingTable::findOrFail($id);
        $table->delete();

        return redirect('/cutting-tables');
    }

    public function searchCuttingTables(Request $request)
    {
        $tables = CuttingTable::withoutGlobalScope('factoryId')
            ->join('cutting_floors', 'cutting_floors.id', '=', 'cutting_tables.cutting_floor_id')
            ->where('cutting_tables.factory_id', factoryId())
            ->where(function ($q) use ($request) {
                return $q->where('cutting_tables.table_no', 'like', '%' . $request->q . '%')
                    ->orWhere('cutting_floors.floor_no', 'like', '%' . $request->q . '%');
            })
            ->select('cutting_tables.*', 'cutting_floors.floor_no as floor_no')
            ->orderBy('cutting_tables.table_no', 'asc')
            ->paginate();

        return view('system-settings::cuttingdroplets.search_cutting_tables', ['tables' => $tables, 'q' => $request->q]);
    }

    public function getCuttingTables($cutting_floor_id)
    {
        return CuttingTable::where('cutting_floor_id', $cutting_floor_id)->pluck('table_no', 'id')->toArray();
    }
}
