<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SkylarkSoft\GoRMG\SystemSettings\Models\Buyer;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsSample;

class GarmentsSampleController extends Controller
{
    public function index()
    {
        $samples = GarmentsSample::with('factory')->orderBy('id', 'desc')->paginate();

        return view('system-settings::samples.index', compact('samples', ));
    }

    public function create()
    {
        $sample = null;
        $buyers = [];

        $factories = Factory::pluck('factory_name', 'id')->prepend('Select Factory', '');

        return view('system-settings::samples.create', compact('factories', 'sample', 'buyers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buyer_id' => 'required|array|min:1',
            'factory_id' => 'required',
            'type' => 'required',
            'name' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
            'status' => 'required',
        ], [
            'required' => 'Required',
        ]);

        try {
            $sample = new GarmentsSample($request->all());
            $sample->save();
            session()->flash('success', 'Successfully Created!');

            return redirect()->to('garments-sample');
        } catch (\Exception $e) {
            session()->flash('error', 'Something Went Wrong!');
            $request->flash();

            return redirect()->back();
        }
    }

    public function edit(GarmentsSample $sample)
    {
        $factories = Factory::pluck('factory_name', 'id')->prepend('Select Factory', '');

        $buyers = Buyer::where('factory_id', $sample->factory_id)->pluck('name', 'id');

        return view('system-settings::samples.create', compact('factories', 'sample', 'buyers'));
    }

    public function update(GarmentsSample $sample, Request $request)
    {
        $request->validate([
            'buyer_id' => 'required|array|min:1',
            'factory_id' => 'required',
            'type' => 'required',
            'name' => 'required',
            'status' => 'required',
        ], [
            'required' => 'Required',
        ]);

        try {
            $sample->fill($request->all());
            $sample->save();
            session()->flash('success', 'Successfully Created!');

            return redirect()->to('garments-sample');
        } catch (\Exception $e) {
            session()->flash('error', 'Something Went Wrong!');
            $request->flash();

            return redirect()->back();
        }
    }

    public function getBuyers($factoryId)
    {
        return Buyer::where('factory_id', $factoryId)->pluck('name', 'id')->map(function ($name, $id) {
            return '<option value=' . $id . '>' . $name  . '</option>';
        })->join('');
    }

    public function save(Request $request)
    {
        try {
            $id = $request->get('id') ?? null;
            if ($id) {
                $data = GarmentsSample::findOrFail($id);
                $data->update($request->all());
            }else {
                $data = GarmentsSample::create($request->all());
            }
            return response()->json(['message' => 'Successfully Saved!', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!', 'error' => $e->getMessage()]);
        }
    }
}
