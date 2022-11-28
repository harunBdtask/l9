<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\SystemSettings\Models\PrintFactory;

class PrintFactoryController extends Controller
{
    public function index()
    {
        $query = PrintFactory::orderBy('id', 'DESC');


        $columns = [
            '' => 'Select Column',
            'factories' => 'Factory',

        ];

        if (request()->has('search')) {
            $searchQuery = '%' . request('q') . '%';

            if (request('column') == 'factories') {
                $query->where('factory_short_name', 'LIKE', $searchQuery);
            } else {
                session()->flash('alert-danger', 'Please select a column you\'re looking for');
            }
        }

        $print_factories = $query->paginate();
        request()->flash();

        return view('system-settings::pages.print_factories', compact('print_factories', 'columns'));


    }

    public function create()
    {
        return view('system-settings::forms.print_factory', ['print_factory' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'factory_name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|max:255|unique:print_factories,factory_name," . request()->route('id'),
            'factory_short_name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i|unique:print_factories,factory_short_name," . request()->route('id'),
            'factory_type' => 'required',
            'factory_address' => 'required',
        ]);

        PrintFactory::create($request->all());

        return redirect('/others-factories');
    }

    public function edit($id)
    {
        $print_factory = PrintFactory::findOrFail($id);

        return view('system-settings::forms.print_factory', ['print_factory' => $print_factory]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'factory_name' => 'required|max:255|unique:print_factories,factory_name,' . request()->route('id'),
            'factory_short_name' => 'required|unique:print_factories,factory_short_name,' . request()->route('id'),
            'factory_type' => 'required',
            'factory_address' => 'required',
        ]);

        $print_factory = PrintFactory::findOrFail($id);
        $print_factory->update($request->all());

        return redirect('/others-factories');
    }

    public function destroy($id)
    {
        $print_factory = PrintFactory::findOrFail($id);
        $print_factory->delete();

        return redirect('/others-factories');
    }

    public function getKnittingFactoriesForSelectSearch(Request $request)
    {
        $search = $request->search;
        $knitting_factories = PrintFactory::query()
            ->where('factory_type', 'knitting')
            ->when($search, function ($q) use ($search) {
                return $q->where('factory_name', 'LIKE', '%' . $search . '%');
            })->limit(20)->get([
                'id',
                'factory_name as text'
            ])->all();

        return response()->json($knitting_factories);

    }
}
