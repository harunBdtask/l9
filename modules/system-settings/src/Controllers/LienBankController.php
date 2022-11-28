<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\SystemSettings\Models\LienBank;
use SkylarkSoft\GoRMG\SystemSettings\Requests\LienBankRequest;

class LienBankController extends Controller
{
    public function index(Request $request)
    {
        $items = LienBank::query();
        $search = $request->get('search');
        if ($search) {
            $items->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('address', 'LIKE', '%' . $search . '%')
                ->orWhere('contact_person', 'LIKE', '%' . $search . '%');
        }
        $items = $items->latest()->paginate();

        return view('system-settings::lian-bank.index', compact('items', 'search'));
    }

    public function store(LienBankRequest $request): \Illuminate\Http\RedirectResponse
    {
        LienBank::query()->create($request->all());
        Session::flash('success', 'Data Created Successfully');

        return redirect()->back();
    }

    public function edit($id)
    {
        return LienBank::query()->findOrFail($id);
    }

    public function update($id, LienBankRequest $request): \Illuminate\Http\RedirectResponse
    {
        Session::flash('success', 'Data Updated Successfully');
        LienBank::query()->findOrFail($id)->update($request->all());

        return redirect()->back();
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        LienBank::query()->findOrFail($id)->delete();

        return redirect()->back();
    }
}
