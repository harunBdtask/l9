<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductType;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ProductTypeRequest;

class ProductTypeController extends Controller
{
    public function index()
    {
        $productTypes = ProductType::with('user')->orderBy('id', 'desc')->paginate();

        return view('system-settings::pages.product_types', compact('productTypes'));
    }

    public function create()
    {
        $productType = null;

        return view('system-settings::forms.product_type', compact('productType'));
    }

    public function store(ProductTypeRequest $request)
    {
        try {
            $data = $request->except('_token', 'id');
            ProductType::create($data);
            Session::flash('alert-success', 'Data Created Successfully');
        } catch (\Exception $exception) {
            Session::flash('alert-danger', 'Something Went Wrong');
        }

        return redirect('/product-types');
    }

    public function edit($id)
    {
        $productType = ProductType::findOrFail($id);

        return view('system-settings::forms.product_type', compact('productType'));
    }

    public function update($id, ProductTypeRequest $request)
    {
        $data = $request->only('name');
        ProductType::findOrFail($id)->update($data);
        Session::flash('alert-success', 'Data Update Successfully');

        return redirect('/product-types');
    }

    public function destroy($id)
    {
        ProductType::findOrFail($id)->delete($id);
        Session::flash('alert-danger', 'Data Deleted Successfully');

        return redirect('/product-types');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $productTypes = ProductType::with('user')->where('name', 'like', '%' . $search . '%')->paginate();

        return view('system-settings::pages.product_types', compact('productTypes', 'search'));
    }
}
