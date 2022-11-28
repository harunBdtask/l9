<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\Merchandising\Models\BudgetCostingDetails;
use SkylarkSoft\GoRMG\Merchandising\Models\CostingDetails;
use SkylarkSoft\GoRMG\SystemSettings\Models\BodyPart;
use SkylarkSoft\GoRMG\SystemSettings\Requests\BodyPartRequest;

class BodyPartController extends Controller
{
    public function index()
    {
        $bodyParts = BodyPart::orderBy('id', 'desc')->paginate();
        $entryPages = $this->entryPages();
        $types = $this->types();

        return view('system-settings::pages.body_parts', compact('bodyParts', 'entryPages', 'types'));
    }

    public function store(BodyPartRequest $request)
    {
        try {
            $data = $request->except('_token', 'entry_page');
            $data['entry_page'] = implode(',', $request->get('entry_page'));
            $body_part = new BodyPart($data);
            $body_part->save();
            Session::flash('success', 'Data Created Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
            $body_part = $exception->getMessage();
        }

        if ($request->ajax()) {
            return response()->json($body_part);
        }

        return redirect('body-parts');
    }

    public function show($id)
    {
        return BodyPart::findOrFail($id);
    }

    public function update($id, BodyPartRequest $request)
    {
        try {
            $data = $request->except('_token', 'entry_page');
            $data['entry_page'] = implode(',', $request->get('entry_page'));
            BodyPart::findOrFail($id)->update($data);
            Session::flash('success', 'Data Updated Successfully');
        } catch (\Exception $exception) {
            Session::flash('error', 'Something Went Wrong');
        }

        return redirect('body-parts');
    }

    public function destroy($id)
    {
        $bodyPartId = CostingDetails::query()->where('type', 'fabric_costing')->get();
        $bodyPartIdPQ = collect($bodyPartId)->pluck('details.details.fabricForm')->flatten(1)->pluck('body_part_id')->map(function ($item) {
            return (int)$item;
        })->unique()->values();

        $budgetCostings = BudgetCostingDetails::query()->where('type', 'fabric_costing')->get();
        $fabricCompositionIdBudget = collect($budgetCostings)->pluck('details.details.fabricForm')->flatten(1)->pluck('body_part_id')->map(function ($item) {
            return (int)$item;
        })->unique()->values();

        if (!(collect($bodyPartIdPQ)->contains($id) || collect($fabricCompositionIdBudget)->contains($id))) {
            BodyPart::findOrFail($id)->delete();
            Session::flash('error', 'Data Deleted Successfully');
        } else {
            Session::flash('error', 'Can Not be Deleted ! It is currently associated with Others');
        }

        return redirect('body-parts');
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        $bodyParts = BodyPart::where('name', 'like', '%' . $search . '%')
            ->orWhere('short_name', 'like', '%' . $search . '%')
            ->orWhere('entry_page', 'like', '%' . $search . '%')
            ->orWhere('type', 'like', '%' . $search . '%')
            ->orWhere('status', 'like', '%' . $search . '%')
            ->orderBy('id', 'desc')->paginate();
        $entryPages = $this->entryPages();
        $types = $this->types();

        return view('system-settings::pages.body_parts', compact('bodyParts', 'entryPages', 'types', 'search'));
    }

    private function entryPages(): array
    {
        return [
            'AOP Batch Creation', 'AOP Bill Issue', 'AOP Delivery Entry', 'AOP Dyes And Chemical Issue Requisition',
        ];
    }

    private function types(): array
    {
        return [
            'Top', 'Bottom', 'Flat Knit', 'Cuff',
        ];
    }

    public function getBodyPartEntryPageOptions()
    {

        $data['entryPages'] = collect($this->entryPages())->map(function ($item) {
            return [
                'id' => $item,
                'text' => $item
            ];
        });
        $data['types'] = collect($this->types())->map(function ($item) {
            return [
                'id' => $item,
                'text' => $item
            ];
        });

        return response()->json($data);
    }
}
