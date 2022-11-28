<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDF;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\Merchandising\Models\PriceQuotation;
use SkylarkSoft\GoRMG\SystemSettings\Models\ProductDepartments;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ProductDepartmentRequest;
use Symfony\Component\HttpFoundation\Response;

class ProductDepartmentController extends Controller
{
    public function index(Request $request)
    {
        $searchKey = $request->get('q');
        $product_departments = ProductDepartments::with('factory')
            ->when($searchKey, function ($query) use ($searchKey) {
                $query->where("product_department", "LIKE", "%$searchKey%");
            })
            ->orderBy('id', 'DESC')
            ->paginate();

        return view('system-settings::pages.product_departments', ['product_departments' => $product_departments]);
    }

    public function create()
    {
        $status = [1 => 'Active', 2 => 'In Active', 3 => 'Cancelled'];

        return view('system-settings::forms.product_department', ['product_departments' => null, 'status' => $status]);
    }

    public function edit($id)
    {
        $product_departments = ProductDepartments::findOrFail($id);
        $status = [1 => 'Active', 2 => 'In Active', 3 => 'Cancelled'];

        return view('system-settings::forms.product_department', ['product_departments' => $product_departments, 'status' => $status]);
    }

    public function store(ProductDepartmentRequest $request)
    {
        try {
            DB::beginTransaction();
            $product_departments = new ProductDepartments();
            $product_departments->product_department = $request->product_department;
            $product_departments->status = $request->status;
            $product_departments->save();
            DB::commit();
            Session::flash('alert-success', 'Data Stored Successfully!!');

            return redirect('product-department');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!ERROR CODE ProductDept.S-101');

            return redirect()->back();
        }
    }

    public function update($id, ProductDepartmentRequest $request)
    {
        try {
            DB::beginTransaction();
            $product_departments = ProductDepartments::find($id);
            $product_departments->product_department = $request->product_department;
            $product_departments->status = $request->status;
            $product_departments->updated_by = Auth::user()->id;
            $product_departments->save();
            DB::commit();
            Session::flash('alert-success', 'Data Updated Successfully!!');

            return redirect('product-department');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert-danger', 'Something went wrong!ERROR CODE ProductDept.U-102');

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $productDepartmentIdPQ = PriceQuotation::query()->get()->pluck('product_department_id')->unique()->values();
        $productDepartmentIdOrders = Order::query()->whereNotNull('product_dept_id')->get()->pluck('product_dept_id')->unique()->values();

        if (!(collect($productDepartmentIdOrders)->contains($id) || collect($productDepartmentIdPQ)->contains($id))) {
            try {
                DB::beginTransaction();
                $product_departments = ProductDepartments::findOrFail($id);
                $product_departments->is_deleted = 1;
                $product_departments->status = 2;
                $product_departments->deleted_by = Auth::id();
                $product_departments->save();
                $product_departments->delete();
                DB::commit();
                Session::flash('alert-success', 'Data Deleted Successfully!!');

                return redirect('product-department');
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('alert-danger', 'Something went wrong!ERROR CODE ProductDept.D-103');

                return redirect()->back();
            }
        } else {
            Session::flash('alert-danger', 'Can Not be Deleted ! It is currently associated with Others');

            return redirect('product-department');
        }
    }

    public function pdfDownload()
    {
        $data['product_departments'] = ProductDepartments::with('factory')->orderBy('factory_id', 'asc')->get();
        $pdf = PDF::loadView('system-settings::pages.product_department_pdf', $data);

        return $pdf->download('product_department.pdf');
    }

    public function selectSearch(Request $request)
    {
        try {
            $search = $request->search ?? null;
            $results = ProductDepartments::query()
            ->when($search, function ($query) use($search) {
                $query->where('product_department', 'like', $search.'%');
            })
            ->limit(30)
            ->get([
                'id',
                'product_department as text'
            ]);

            return response()->json([
                'status' => Response::HTTP_OK,
                'results' => $results,
                'errors' => null
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'results' => [],
                'errors' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function save(ProductDepartmentRequest $request)
    {
        try {
            $id = $request->get('id') ?? null;
            if ($id) {
                $data = ProductDepartments::findOrFail($id);
                $data->update($request->all());
            }else {
                $data = ProductDepartments::create($request->all());
            }
            return response()->json(['message' => 'Successfully Saved!', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!', 'error' => $e->getMessage()]);
        }
    }
}
