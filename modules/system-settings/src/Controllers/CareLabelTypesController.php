<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\CareLabelType;
use SkylarkSoft\GoRMG\SystemSettings\Requests\CareLabelTypeFormRequest;

class CareLabelTypesController extends Controller
{
    public function index()
    {
        $careLabelTypes = CareLabelType::query()->orderByDesc('id')->paginate();

        return view('system-settings::pages.care_label_types', [
            'careLabelType' => null,
            'careLabelTypes' => $careLabelTypes,
        ]);
    }

    public function store(CareLabelTypeFormRequest $request, CareLabelType $careLabelType)
    {
        try {
            $careLabelType->fill($request->all())->save();

            Session::flash('alert-success', 'Data Stored Successfully');

            return redirect('care-label-types');
        } catch (Exception $e) {
            Session::flash('alert-danger', $e->getMessage());

            return redirect()->back();
        }
    }

    public function edit(CareLabelType $careLabelType): CareLabelType
    {
        return $careLabelType;
    }

    public function update(CareLabelTypeFormRequest $request, CareLabelType $careLabelType)
    {
        try {
            $careLabelType->fill($request->all())->save();

            Session::flash('alert-success', 'Data Updated Successfully');

            return redirect('care-label-types');
        } catch (Exception $e) {
            Session::flash('alert-danger', $e->getMessage());

            return redirect()->back();
        }
    }

    public function destroy(CareLabelType $careLabelType)
    {
        try {
            $careLabelType->delete();

            Session::flash('alert-success', 'Data Deleted Successfully');

            return redirect('care-label-types');
        } catch (Exception $e) {
            Session::flash('alert-danger', $e->getMessage());

            return redirect()->back();
        }
    }
}
