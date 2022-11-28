<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\DyeingCompany;
use SkylarkSoft\GoRMG\SystemSettings\Requests\DyeingCompanyRequest;

class DyeingCompanyController extends Controller
{
    public function index()
    {
        $dyeingCompanys = DyeingCompany::query()->paginate();
        return view('system-settings::DyeingCompany.index',[
            'dyeingCompanys' => $dyeingCompanys
        ]);
    }

    public function create()
    {
        # code...
    }

    public function store(DyeingCompanyRequest $request)
    {
        try {
            DyeingCompany::create($request->all());
            Session::flash('success', 'Data Created successfully');
        } catch(Exception $exception){
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('/dyeing-company');
    }

    public function edit($id)
    {
        return DyeingCompany::findOrFail($id);
    }

    public function update(DyeingCompanyRequest $request, $id)
    {
        try {
            DyeingCompany::findOrFail($id)->update($request->all());
            Session::flash('success', 'Data Updated Successfully!!');
        } catch(Exception $exception){
            Session::flash('error', 'Something went wrong');
        }
        return redirect('/dyeing-company');
    }

    public function destroy($id)
    {
        $dyeingCompany = DyeingCompany::findOrFail($id);
        $dyeingCompany->delete();
        Session::flash('error', 'Data Deleted Successfully');
        return redirect('/dyeing-company');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $dyeingCompanys = DyeingCompany::where('name', 'like', '%' . $search . '%')->paginate();

        return view('system-settings::DyeingCompany.index', ['dyeingCompanys' => $dyeingCompanys, 'search' => $search]);
    }
}