<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\UtitlityController;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Hash;
use App\Constants\ApplicationConstant;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\SystemSettings\Models\Role;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\SystemSettings\Models\Factory;
use SkylarkSoft\GoRMG\SystemSettings\Models\Department;
use SkylarkSoft\GoRMG\SystemSettings\Requests\UpdateUser;

class UserManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        if (getRole() != 'super-admin') {
            $users = User::where('factory_id', factoryId())
                ->where('role_id', '!=', 1)
                ->orderBy('id', 'DESC')
                ->paginate();
        } else {
            $users = User::orderBy('id', 'DESC')->paginate();
        }

        return view('system-settings::pages.users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        $factories = Factory::pluck('factory_name', 'id')->all();
        $departments = Department::pluck('department_name', 'id')->all();
        $dashboard_versions = User::DASHBOARD_VERSIONS;
        if (getRole() == 'super-admin') {
            $roles = Role::pluck('name', 'id')->all();
        } else {
            $roles = Role::where('slug', '!=', 'super-admin')->pluck('name', 'id')->all();
        }

        return view('system-settings::forms.user', [
            'user' => null,
            'factories' => $factories,
            'roles' => $roles,
            'departments' => $departments,
            'dashboard_versions' => $dashboard_versions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => "required|max:50|not_regex:/([^\w\d\s&'.\-\_\)\(\/])+/i",
            'department' => 'required|integer',
            'factory_id' => 'required|integer',
            'role_id' => 'required|integer',
            'email' => 'required|unique:users|max:55',
            'password' => 'required|min:6',
            'dashboard_version' => 'nullable',
            'confirm_password' => 'required|same:password',
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:max_width=499,max_height=498',
        ], [
            'profile_image.dimensions' => 'Profile image dimensions must be 499X498',
            'required' => 'This field is required'
        ]);

        try {
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->screen_name = $request->first_name . ' ' . $request->last_name;
            $user->designation = $request->designation;
            $user->address = $request->address;
            $user->phone_no = $request->phone_no;
            $user->factory_id = $request->factory_id;
            $user->role_id = $request->role_id;
            $user->department = $request->department;
            $user->email = $request->email;
            $user->dashboard_version = $request->dashboard_version;
            $user->password = bcrypt($request->password);
            if ($request->hasFile('profile_image')) {
                $time = time();
                $file = $request->profile_image;
                $file->storeAs('profile_image', $time . $file->getClientOriginalName());
                $user->profile_image = $time . $file->getClientOriginalName();
            }
            $user->save();
            Session::flash('success', 'Data Created Successfully');

            return redirect('/users');
        } catch (\Exception $e) {
            Session::flash('error', 'Something wrong!!. Please try again');

            return redirect()->back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        if ($user->role_id == 1) {
            Session::flash('error', 'You Cannot Update Super Admin User!!');

            return redirect()->back();
        }
        $factories = Factory::pluck('factory_name', 'id')->all();
        $departments = Department::pluck('department_name', 'id')->all();
        if (getRole() == 'super-admin') {
            $roles = Role::pluck('name', 'id')->all();
        } else {
            $roles = Role::where('slug', '!=', 'super-admin')->pluck('name', 'id')->all();
        }
        $dashboard_versions = User::DASHBOARD_VERSIONS;

        return view('system-settings::forms.user', [
            'user' => $user,
            'factories' => $factories,
            'roles' => $roles,
            'departments' => $departments,
            'dashboard_versions' => $dashboard_versions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param UpdateUser $request
     * @return Application|Redirector|RedirectResponse
     */
    public function update($id, UpdateUser $request)
    {
        try {
            $user = User::findOrFail($id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->screen_name = $request->first_name . ' ' . $request->last_name;
            $user->designation = $request->designation;
            $user->address = $request->address;
            $user->phone_no = $request->phone_no;
            $user->factory_id = $request->factory_id;
            $user->role_id = $request->role_id;
            $user->department = $request->department;
            $user->email = $request->email;
            $user->dashboard_version = $request->dashboard_version;
            if ($request->password != null) {
                $user->password = Hash::make($request->password);
            }
            if ($request->hasFile('profile_image')) {
                $file_name_to_delete = $user->profile_image;
                if ($file_name_to_delete && Storage::disk('public')->exists('/profile_image/' . $file_name_to_delete)) {
                    Storage::delete('profile_image/' . $file_name_to_delete);
                }
                $time = time();
                $file = $request->profile_image;
                $file->storeAs('profile_image', $time . $file->getClientOriginalName());
                $user->profile_image = $time . $file->getClientOriginalName();
            }
            $user->save();
            Session::flash('success', 'Data Updated Successfully');

            return redirect('/users');
        } catch (\Exception $e) {
            Session::flash('error', 'Something wrong!!. Please try again');

            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        if (User::destroy($id)) {
            Session::flash('success', 'Successfully deleted');
        } else {
            Session::flash('error', 'Not successfully deleted');
        }

        return redirect()->back();
    }

    public function searchUsers(Request $request)
    {
        $users = User::withoutGlobalScope('factoryId')
            ->join('factories', 'factories.id', '=', 'users.factory_id')
            ->join('departments', 'departments.id', '=', 'users.department')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->where('users.first_name', 'like', '%' . $request->q . '%')
            ->orWhere('users.last_name', 'like', '%' . $request->q . '%')
            ->orWhere('users.designation', 'like', '%' . $request->q . '%')
            ->orWhere('users.address', 'like', '%' . $request->q . '%')
            ->orWhere('users.phone_no', 'like', '%' . $request->q . '%')
            ->orWhere('users.email', 'like', '%' . $request->q . '%')
            ->orWhere('factories.factory_name', 'like', '%' . $request->q . '%')
            ->orWhere('departments.department_name', 'like', '%' . $request->q . '%')
            ->orWhere('roles.name', 'like', '%' . $request->q . '%')
            ->select('users.*', 'factories.factory_name as factory_name', 'departments.department_name as department_name', 'roles.name as role_name', 'roles.slug as role_slug')
            ->orderBy('users.id', 'DESC')
            ->paginate();

        return view('system-settings::pages.search_users', ['users' => $users, 'q' => $request->q]);
    }

    public function getUsers($factory_id)
    {
//        return User::getUsers($factory_id);
        //FIXME;
        return User::query()
//            ->where('factory_id', $factory_id)
//            ->orWhere('role_id', ApplicationConstant::MERCHANDISER_ROLE_ID)
            ->select('first_name', 'last_name', 'screen_name', 'email', 'id')
            ->orderBy('email', 'asc')
            ->get();
    }

    public function getAllUser(): JsonResponse
    {
        $users = User::query()->get()->map(function ($user) {
            $user['text'] = $user->full_name;
            return $user;
        });
        return response()->json($users, Response::HTTP_OK);
    }

    public function selectSearch(Request $request)
    {
        try {
            $search = $request->search ?? null;
            $results = User::query()
                ->when($search, function ($query) use ($search) {
                    $query->where('screen_name', 'like', $search . '%');
                })
                ->limit(30)
                ->get([
                    'id',
                    'screen_name as text'
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
}
