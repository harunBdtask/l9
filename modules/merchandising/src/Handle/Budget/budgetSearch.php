<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/19/19
 * Time: 12:10 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Budget;

use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Models\PreBudget;

class budgetSearch
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get()
    {
        $user = Auth::user();
        $query_string = $this->request->query_string;
        $column_name = $this->request->column_name;
        $query = PreBudget::with('buyer')->orderBy('id', 'desc');

        switch ($column_name) {
            case 'job_number':
                $query->where('job_number', 'LIKE', $query_string);

                break;
            case "buyer":
                $query->whereHas('buyer', function ($q) use ($query_string) {
                    $q->where('name', 'LIKE', "$query_string%");
                });

                break;
            default:
                return false;
        }

        /* if role is admin or super admin */
        if ($user->role_id != 1 && $user->role_id != 2) {
            $list_view_permission_ids = get_list_view_permission($user);
            $query->whereIn('created_by', $list_view_permission_ids);
        }

        return $query->paginate();
    }
}
