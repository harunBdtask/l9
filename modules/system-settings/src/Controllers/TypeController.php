<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\Type;
use SkylarkSoft\GoRMG\SystemSettings\Requests\TypeRequest;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.types', ['types' => $types]);
    }

    public function create()
    {
        return view('system-settings::forms.type', ['type' => null]);
    }

    public function store(TypeRequest $request)
    {
        Type::create($request->all());
        Session::flash('alert-success', 'Data stored successfully!!');

        return redirect('/types');
    }

    public function edit($id)
    {
        $type = Type::findOrFail($id);

        return view('system-settings::forms.type', ['type' => $type]);
    }

    public function update($id, TypeRequest $request)
    {
        $type = Type::findOrFail($id);
        $type->update($request->all());
        Session::flash('alert-success', 'Data updated successfully!!');

        return redirect('/types');
    }

    public function destroy($id)
    {
        $type = Type::findOrFail($id);
        $type->delete();
        Session::flash('alert-danger', 'Data deleted successfully!!');

        return redirect('/types');
    }

    public function searchTypes(Request $request)
    {
        $types = Type::where('name', 'like', '%' . $request->q . '%')
            ->orderBy('id', 'DESC')->paginate();

        return view('system-settings::pages.types', ['types' => $types, 'q' => $request->q]);
    }
}
