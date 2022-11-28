<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Jobs;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use SkylarkSoft\GoRMG\BasicFinance\Models\ChequeBookDetail;

class ChequeNoUpdateJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle()
    {
        if (isset($this->request['type_id']) && $this->request['type_id'] == 1 && $this->request['paymode'] == 1) {
            $chequeBookDetail = ChequeBookDetail::query()->findOrFail($this->request['cheque_no']);
            $chequeBookDetail->fill($this->request->merge([
                'paid_to' => $this->request['to']
            ])->except('cheque_no'))->save();
        }
    }
}
