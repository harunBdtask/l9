<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers\Inventory;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\DyeingFloor;
use SkylarkSoft\GoRMG\SystemSettings\Models\ServiceCompany;
use SkylarkSoft\GoRMG\SystemSettings\Requests\ServiceCompanyRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class ServiceCompanyController extends Controller
{
    protected function serviceCompanies(Request $request): LengthAwarePaginator
    {
        return ServiceCompany::query()
            ->when($request->get('name'), function ($query) use ($request) {
                $query->where('name', "{$request->get('name')}");
            })
            ->when($request->get('address'), function ($query) use ($request) {
                $query->where('address', 'LIKE', "%{$request->get('address')}%");
            })->paginate();
    }

    public function index(Request $request)
    {
        $serviceCompanies = $this->serviceCompanies($request);
        return view('system-settings::inventory.service_company.service_company', [
            'serviceCompany' => null,
            'serviceCompanies' => $serviceCompanies
        ]);
    }

    public function store(ServiceCompanyRequest $request, ServiceCompany $serviceCompany): RedirectResponse
    {
        try {
            $serviceCompany->fill($request->all())->save();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('service-company.index');
        }
    }

    public function edit(Request $request, ServiceCompany $serviceCompany)
    {
        try {

            $serviceCompanies = $this->serviceCompanies($request);
            return view('system-settings::inventory.service_company.service_company', [
                'serviceCompany' => $serviceCompany,
                'serviceCompanies' => $serviceCompanies
            ]);
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());

            return back();
        }
    }

    public function update(ServiceCompanyRequest $request, ServiceCompany $serviceCompany): RedirectResponse
    {
        try {
            $serviceCompany->fill($request->all())->save();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return redirect()->route('service-company.index');
        }
    }

    public function destroy(ServiceCompany $serviceCompany): RedirectResponse
    {
        try {
            $serviceCompany->delete();
        } catch (Exception $exception) {
            Session::flash('alert-danger', $exception->getMessage());
        } finally {
            return back();
        }
    }
}
