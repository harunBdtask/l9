<?php

namespace SkylarkSoft\GoRMG\HR\Controllers\Api\Setting;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;
use SkylarkSoft\GoRMG\HR\Models\HrGroup;
use SkylarkSoft\GoRMG\HR\Requests\GroupRequest;

class GroupController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $searchKey = $request->get('search');

        $groups = $this->getPaginatedGroups($searchKey);

        return view('hr::group.index', ['groups' => $groups]);
    }

    /**
     * @param GroupRequest $request
     * @param HrGroup $hrGroup
     * @return RedirectResponse
     */
    public function store(GroupRequest $request, HrGroup $hrGroup): RedirectResponse
    {
        try {
            $hrGroup->fill($request->all())->save();

            Session::flash("success", "Successfully Saved");
        } catch (Exception $exception) {
            Session::flash("error", "Something went Wrong");
        } finally {
            return redirect('/hr/groups');
        }
    }

    /**
     * @param HrGroup $hrGroup
     * @return Application|Factory|View
     */
    public function edit(HrGroup $hrGroup)
    {
        $groups = $this->getPaginatedGroups();
        return view('hr::group.index', ['group' => $hrGroup, 'groups' => $groups]);
    }

    /**
     * @param GroupRequest $request
     * @param HrGroup $hrGroup
     * @return Application|RedirectResponse|Redirector
     */
    public function update(GroupRequest $request, HrGroup $hrGroup)
    {
        try {
            $hrGroup->fill($request->all())->save();

            Session::flash("success", "Successfully Updated");
        } catch (Exception $exception) {
            Session::flash("error", "Something went Wrong");
        } finally {
            return redirect('/hr/groups');
        }
    }

    /**
     * @param null $searchKey
     * @return LengthAwarePaginator
     */
    protected function getPaginatedGroups($searchKey = null): LengthAwarePaginator
    {
        return HrGroup::query()
            ->when($searchKey, function ($query) use ($searchKey) {
                $query->where('name', 'LIKE', "%{$searchKey}%");
            })
            ->orderBy('id', 'DESC')
            ->paginate();
    }
}
