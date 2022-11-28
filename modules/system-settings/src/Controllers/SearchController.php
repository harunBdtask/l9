<?php

namespace App\Http\Controllers;

use App\Models\Line;
use App\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchUsers(Request $request)
    {
        if (! isset($request->q)) {
            return redirect('users');
        }

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
            ->select('users.*', 'factories.factory_name as factory_name', 'departments.department_name as department_name', 'roles.name as role_name')
            ->orderBy('users.id', 'DESC')
            ->paginate();

        return view('pages.search_users', ['users' => $users, 'q' => $request->q]);
    }

    public function searchLines(Request $request)
    {
        if (! isset($request->q)) {
            return redirect('lines');
        }

        $lines = Line::withoutGlobalScope('factoryId')
            ->join('floors', 'floors.id', '=', 'lines.floor_id')
            ->where('lines.factory_id', factoryId())
            ->where(function ($q) use ($request) {
                return $q->where('lines.line_no', 'like', '%' . $request->q . '%')
                    ->orWhere('floors.floor_no', 'like', '%' . $request->q . '%');
            })
            ->select('lines.*', 'floors.floor_no')
            ->orderBy('lines.line_no', 'asc')
            ->paginate();

        return view('pages.search_lines', ['lines' => $lines, 'q' => $request->q]);
    }
}
