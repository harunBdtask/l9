<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use OwenIt\Auditing\Models\Audit;
use SkylarkSoft\GoRMG\SystemSettings\Exports\AuditExport;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserAuditController extends Controller
{
//    public function index()
//    {
//        $audits = Audit::query()
//            ->whereJsonLength('old_values', '!=', 0)
//            ->latest()->limit(30)->get();
//        $userIds = collect($audits)->pluck('user_id')->unique();
//        $users = User::query()->whereIn('id', $userIds)->latest()->limit(30)->get();
//        $audits = collect($audits)->map(function ($d) use ($users){
//            $d['user'] = collect($users)->where('id', $d['user_id'])->first();
//            return $d;
//        });
//        return view('system-settings::audit.index', compact('audits'));
//    }

    public function index(Request $request)
    {
        $date = $request->get('date') ?? date('Y-m-d');

//        Audit::query()
//            ->whereJsonContains('new_values->status', false)
//            ->delete();

        $audits = Audit::query()
            ->whereDate('created_at', $date)
            ->latest()
            ->get();

        return view('system-settings::audit.index', compact('audits', 'date'));
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws Exception
     */
    public function excel(Request $request): BinaryFileResponse
    {
        $date = $request->get('date') ?? date('Y-m-d');
        return Excel::download(new AuditExport($date), 'Audit ('. $date .').xlsx');
    }
}
