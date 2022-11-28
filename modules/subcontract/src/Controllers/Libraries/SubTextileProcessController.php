<?php

namespace SkylarkSoft\GoRMG\Subcontract\Controllers\Libraries;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileOperation;
use SkylarkSoft\GoRMG\Subcontract\Models\Libraries\SubTextileProcess;
use SkylarkSoft\GoRMG\Subcontract\Requests\Libraries\SubTextileProcessFormRequest;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;

class SubTextileProcessController extends Controller
{
    public function index(Request $request)
    {
        $operation = $request->get('operation');
        $name = $request->get('name');
        $price = $request->get('price');
        $company = $request->get('company');

        $processes = SubTextileProcess::query()
            ->when($operation, function (Builder $query) use ($operation) {
                $query->whereHas('textileOperation', function (Builder $q) use ($operation) {
                    $q->where('name', 'LIKE', "%$operation%");
                });
            })->when($name, function (Builder $query) use ($name) {
                $query->where('name', 'LIKE', "%$name%");
            })->when($price, function (Builder $query) use ($price) {
                $query->where('price', 'LIKE', "%$price%");
            })->when($company, function (Builder $query) use ($company) {
                $query->where('factory_id', "$company");
            })->paginate();

        $factories = Factory::all();
        $operations = SubTextileOperation::all();

        return view(
            'subcontract::libraries.sub_textile_process',
            compact('processes', 'factories', 'operations')
        );
    }

    /**
     * @param SubTextileProcessFormRequest $request
     * @return RedirectResponse
     */
    public function storeAndUpdate(SubTextileProcessFormRequest $request): RedirectResponse
    {
        try {
            $id = $request->get('id');
            SubTextileProcess::query()->updateOrCreate(
                ['id' => $id],
                $request->all()
            );
        } catch (Exception $e) {
            Session::flash('alert-danger', $e->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    /**
     * @param SubTextileProcess $process
     * @return JsonResponse
     */
    public function edit(SubTextileProcess $process): JsonResponse
    {
        return response()->json($process);
    }

    /**
     * @param SubTextileProcess $process
     * @return RedirectResponse
     */
    public function status(SubTextileProcess $process): RedirectResponse
    {
        try {
            $process->status = $process->status === 1 ? 0 : 1;
            $process->save();

            Session::flash('alert-success', "Successfully Status changed");
        } catch (Exception $e) {
            Session::flash('alert-danger', $e->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
