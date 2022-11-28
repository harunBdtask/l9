<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\LienBank;
use SkylarkSoft\GoRMG\SystemSettings\Rules\UniqueFactory;

class FactoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index(Request $request)
    {
        $searchKey = $request->get('q');
        $factories = Factory::query()
            ->when($searchKey, function ($query) use ($searchKey) {
                $query->where('group_name', 'LIKE', "%$searchKey%")
                    ->orWhere('factory_name', 'LIKE', "%$searchKey%")
                    ->orWhere('factory_short_name', 'LIKE', "%$searchKey%")
                    ->orWhere('factory_address', 'LIKE', "%$searchKey%")
                    ->orWhere('responsible_person', 'LIKE', "%$searchKey%")
                    ->orWhere('phone_no', 'LIKE', "%$searchKey%");
            })
            ->orderBy('id', 'desc')
            ->paginate();

        return view('system-settings::pages.factories', [
            'factories' => $factories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|\Illuminate\Contracts\View\Factory|View
     */
    public function create()
    {
        $lienBanks = LienBank::pluck('name', 'id')->all();
        $associateFactories = Factory::pluck('factory_name', 'id');
        return view('system-settings::forms.create_factory', compact('lienBanks', 'associateFactories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $request->validate([
            'group_name' => ['required', "not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i", new UniqueFactory()],
            'factory_name' => "required|max:255|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
            'factory_short_name' => "required|unique:factories|max:15|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
            'factory_address' => 'required|max:255',
            'factory_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        try {
            $input = [
                'group_name' => $request->group_name,
                'factory_name' => $request->factory_name,
                'factory_name_bn' => $request->factory_name_bn,
                'factory_short_name' => preg_replace('/\s+/', '', $request->factory_short_name),
                'factory_address' => $request->factory_address,
                'factory_address_bn' => $request->factory_address_bn,
                'responsible_person' => $request->responsible_person,
                'phone_no' => $request->phone_no,
                'associate_factories' => $request->associate_factories,
            ];

            if ($request->hasFile('factory_image')) {
                $time = time();
                $file = $request->file('factory_image');
                $fileName = $file->getClientOriginalName();
                $file->storeAs('factory_image', $time . $fileName);
                $input['factory_image'] = $time . $fileName;
            }

            $factory = Factory::create($input);
            $factory->lienBanks()->sync($request->input('lien_bank_id'));
            // for factory drodown caching
            Factory::getFactories();
            Session::flash('success', 'Data Created Successfully');
        } catch (Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return redirect('factories');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function edit($id): View
    {
        $factory = Factory::findOrFail($id);
        $lienBanks = LienBank::pluck('name', 'id')->all();
        $associateFactories = Factory::query()
            ->where('id', '!=', $id)
            ->pluck('factory_name', 'id');
        $lienBankId = collect($factory->lienBanks)->pluck('pivot.lien_bank_id');

        return view('system-settings::forms.edit_factory', [
            'factory' => $factory,
            'lienBanks' => $lienBanks,
            'lienBankId' => $lienBankId,
            'associateFactories' => $associateFactories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Application|RedirectResponse|Redirector
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'factory_name' => 'required|max:50',
            'factory_short_name' => 'required|max:15|unique:factories,factory_short_name,' . $id,
            'factory_address' => 'required',
            'factory_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ]);

        try {
            $input = [
                'group_name' => $request->group_name,
                'factory_name' => $request->factory_name,
                'factory_name_bn' => $request->factory_name_bn,
                'factory_short_name' => preg_replace('/\s+/', '', $request->factory_short_name),
                'factory_address' => $request->factory_address,
                'factory_address_bn' => $request->factory_address_bn,
                'responsible_person' => $request->responsible_person,
                'phone_no' => $request->phone_no,
                'associate_factories' => $request->associate_factories,
            ];
            if ($request->hasFile('factory_image')) {
                if (Factory::where('id', $id)->count() > 0) {
                    $file_name_to_delete = Factory::where('id', $id)->first()->factory_image;
                    if ($this->hasPrevImg($file_name_to_delete)) {
                        Storage::delete('factory_image/' . $file_name_to_delete);
                    }
                }
                $time = time();
                $file = $request->file('factory_image');
                $fileName = $file->getClientOriginalName();
                $file->storeAs('factory_image', $time . $fileName);
                $input['factory_image'] = $time . $fileName;
            }

            $factory = Factory::findOrFail($id);
            $factory->update($input);
            $factory->lienBanks()->sync($request->input('lien_bank_id'));

            // for factory drodown caching
            Factory::getFactories();
            Session::flash('success', 'Data Updated Successfully');
        } catch (Exception $e) {
            Session::flash('error', $e->getMassage());
        }

        return redirect('factories');
    }

    /**
     * @param $file_name_to_delete
     * @return bool
     */
    public function hasPrevImg($file_name_to_delete): bool
    {
        return Storage::disk('public')
                ->exists('/factory_image/' . $file_name_to_delete) && $file_name_to_delete != null;
    }

    public function selectAndSearch(Request $request)
    {
        try {
            $search = $request->search ?? null;
            $data = Factory::query()
                ->select('id', 'factory_name', 'factory_address')
                ->when($search, function ($q) use ($search) {
                    return $q->where('factory_name', $search);
                })
                ->get()
                ->map(function ($item, $key) {
                    $data['id'] = $item->id;
                    $data['text'] = $item->factory_name;
                    $data['factory_address'] = $item->factory_address;
                    return $data;
                });
            return [
                'data' => $data,
                'errors' => null,
                'message' => 'Success',
                'status' => 200,
            ];
        } catch (Exception $e) {
            return response()->json([
                'data' => [],
                'status' => 500,
                'errors' => $e->getMessage(),
            ]);
        }
    }
}
