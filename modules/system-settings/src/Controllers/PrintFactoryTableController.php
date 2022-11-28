<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\FinishingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\PrintFactoryTable;

class PrintFactoryTableController extends Controller
{
    public function index(Request $request)
    {
        $tables = PrintFactoryTable::query()
            ->where([
                'factory_id' => Auth::user()->factory_id
            ])
            ->orderBy('id', 'DESC')
            ->paginate();
        request()->flash();

        return view('system-settings::pages.print_tables', compact('tables'));
    }

    public function create()
    {
        $table = null;

        return view('system-settings::forms.print_tables', compact('table'));
    }

    public function store(Request $request)
    {
        $factories = Factory::query()->pluck('id', 'id');
        $request->validate([
            'name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:print_factory_tables,name," . $request->get('id') . ',id',
        ], [
            'name.required' => 'Name is required!',
            'name.unique' => 'Name should be unique',
        ]);
        $printFactoryTable = new PrintFactoryTable();
        $printFactoryTable['factory_id'] = $factories;
        $printFactoryTable->fill($request->all())->save();
        session()->flash('alert-success', S_SAVE_MSG);

        return redirect()->to('print-factory-tables');
    }

    public function edit(PrintFactoryTable $table)
    {
        return view('system-settings::forms.print_tables', compact('table'));
    }

    public function update(PrintFactoryTable $table, Request $request)
    {
        $request->validate([
            'name' => 'required|unique:finishing_floors,name,' . $request->get('id') . ',id',
        ], [
            'name.required' => 'Name is required!',
            'name.unique' => 'Name should be unique!',
        ]);

        $table->update([
            'name' => $request->name,
        ]);

        session()->flash('alert-success', S_UPDATE_MSG);

        return redirect()->to('print-factory-tables');
    }

    public function delete(PrintFactoryTable $table)
    {
        try {
            $table->delete();
            session()->flash('alert-success', S_DEL_MSG);
        } catch (Exception $e) {
            session()->flash('alert-danger', E_DEL_MSG);
        }

        return redirect()->to('print-factory-tables');
    }
}
