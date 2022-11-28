<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LogAudit;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class AuditController extends Controller
{
    protected function logAuditBook(Request $request): LengthAwarePaginator
    {
        //dd($request);
        return LogAudit::query()
            ->when($request->get('date_filter'), function ($query) use ($request) {
                $query->where('date', "{$request->get('date_filter')}");
            })
            ->when($request->get('user_filter'), function ($query) use ($request) {
                $query->where('user_id', "{$request->get('user_filter')}");
            })
            ->when($request->get('month_filter'), function ($query) use ($request) {
                $startOfMonth = $request->get('month_filter').'-01';
                $endOfMonth = Carbon::make($startOfMonth)->endOfMonth();
                $query->where('date','>=',"{$startOfMonth}")
                      ->where('date','<=',"{$endOfMonth}");
            })
            ->when($request->get('module_filter'), function ($query) use ($request) {
                $query->where('module','LIKE', "%{$request->get('module_filter')}%");
            })
            ->orderBy('id', 'DESC')
            ->paginate();
    }

    public function view(Request $request)
    {
        $audits = $this->logAuditBook($request);
        $users = User::query()->pluck('screen_name','id')->prepend('Select','');
        return view('system-settings::audit-log-book.list',[
            'audits' => $audits,
            'users' => $users
        ]);
    }

    public function auditDetails(Request $request)
    {
        $oldValueNewValueId = $request->oldAndNewValue;
        $getOldAndNewValue = LogAudit::query()->where('id',$oldValueNewValueId)->orderBy('id', 'DESC')->first();
        return response()->json($getOldAndNewValue);
    }
}
