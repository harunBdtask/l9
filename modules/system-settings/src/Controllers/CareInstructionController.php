<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\CareInstruction;

class CareInstructionController extends Controller
{
    public function index()
    {
        $careInstructions = CareInstruction::query()->latest()->paginate();

        return view('system-settings::pages.care_instructions', compact('careInstructions'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request, CareInstruction $careInstruction): RedirectResponse
    {
        $request->validate([
            'instruction' => "required|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
        ]);

        $careInstruction->fill($request->all())->save();
        Session::flash('success', 'Successfully Stored');
        return redirect()->back();
    }

    /**
     * @param CareInstruction $careInstruction
     * @return JsonResponse
     */
    public function edit(CareInstruction $careInstruction): JsonResponse
    {
        return response()->json($careInstruction);
    }

    /**
     * @param Request $request
     * @param CareInstruction $careInstruction
     * @return RedirectResponse
     */
    public function update(Request $request, CareInstruction $careInstruction): RedirectResponse
    {
        $request->validate([
            'instruction' => 'required',
        ]);

        $careInstruction->fill($request->all())->save();
        Session::flash('success', 'Successfully Updated');
        return redirect()->back();
    }

    /**
     * @param CareInstruction $careInstruction
     * @return RedirectResponse
     */
    public function destroy(CareInstruction $careInstruction): RedirectResponse
    {
        $careInstruction->delete();
        Session::flash('success', 'Successfully Deleted');
        return redirect()->back();
    }
}
