<?php

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Sample;

use Illuminate\Support\Facades\DB;
use SkylarkSoft\GoRMG\Merchandising\Handle\Sample\Interfaces\SampleInterface;
use SkylarkSoft\GoRMG\Merchandising\Models\Sample;

class SampleStore implements SampleInterface
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function handle(): bool
    {
        $id = $this->request->id ?? '';
//        try {
        DB::beginTransaction();
        $sample = Sample::findOrNew($id);
        $sample->remarks = $this->request->remarks;
        $sample->buyer_id = $this->request->buyer_id;
        $sample->agent_id = $this->request->agent_id;
        $sample->sample_ref_no = $this->request->sample_ref_no;
        $sample->receive_date = date('Y-m-d', strtotime($this->request->receive_date));
        $sample->team_leader = $this->request->team_leader;
        $sample->dealing_merchant = $this->request->dealing_merchant;
        $sample->season = $this->request->season;
        $sample->currency = $this->request->currency;
        $sample->remarks = $this->request->update_remark;
        if ($this->request->hasFile('sample_files')) {
            $sample->sample_files = $this->request->file('sample_files')->store('sample_files');
        }
        $sample->save();
        /* Add Sample Details Table */
        if ($this->request->item_id) {
            (new SampleDetailsStore($this->request, $sample))->handle();
        }

        DB::commit();

        return true;
    }
}
