<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/19/19
 * Time: 12:10 PM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Sample;

use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Models\Sample;

class SampleSearch
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
        $query = Sample::with('buyer', 'agent', 'sampleDetails', 'dealingMerchant', 'teamLead')->orderBy('id', 'desc');

        switch ($column_name) {
            case "buyer":
                $query->whereHas('buyer', function ($q) use ($query_string) {
                    $q->where('name', 'LIKE', "$query_string%");
                });

                break;
            case "agent":
                $query->orWhereHas('agent', function ($q) use ($query_string) {
                    $q->where('buying_agent_name', 'LIKE', "%$query_string%");
                });

                break;
            case "season":
                $query->where('season', 'LIKE', "%$query_string%");

                break;
            case "ref_no":
                $query->where('sample_ref_no', 'LIKE', "%$query_string%");

                break;
            case "dealing_merchant":
                $query->orWhereHas('dealingMerchant', function ($q) use ($query_string) {
                    $q->where('first_name', 'LIKE', "%$query_string%");
                    $q->orWhere('last_name', 'LIKE', "%$query_string%");
                    $q->orWhere('screen_name', 'LIKE', "%$query_string%");
                });

                break;
            case "team_lead":
                $query->whereHas('teamLead', function ($q) use ($query_string) {
                    $q->where('first_name', 'LIKE', "%$query_string%");
                    $q->orWhere('last_name', 'LIKE', "%$query_string%");
                    $q->orWhere('screen_name', 'LIKE', "%$query_string%");
                });

                break;
            case "receive_date":
                $date = date('Y-m-d', strtotime($query_string));
                $query->where('receive_date', 'LIKE', "%$date%");

                break;
            default:
                return false;
        }

        return $query->paginate();
    }
}
