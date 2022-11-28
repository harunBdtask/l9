<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/19/19
 * Time: 11:18 AM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Sample;

use Illuminate\Support\Facades\Auth;
use SkylarkSoft\GoRMG\Merchandising\Handle\Sample\Interfaces\SampleInterface;
use SkylarkSoft\GoRMG\Merchandising\Models\SampleDetail;
use SkylarkSoft\GoRMG\SystemSettings\Models\NewFabricComposition;

class SampleDetailsStore implements SampleInterface
{
    private $request;
    private $sample;

    public function __construct($request, $sample)
    {
        $this->request = $request;
        $this->sample = $sample;
    }

    public function handle()
    {
        if (isset($this->request->id)) {
            SampleDetail::where('sample_id', $this->request->id)->forceDelete();
        }
        $details = [];
        foreach ($this->request->item_id as $key => $value) {
            $details[$key]['created_at'] = date('Y-m-d');
            $details[$key]['updated_at'] = date('Y-m-d');
            $details[$key]['sample_id'] = $this->sample->id;
            $details[$key]['item_id'] = $this->request->item_id[$key];
            $details[$key]['gsm'] = $this->request->gsm[$key];
            $details[$key]['unit_price'] = $this->request->unit_price[$key];
            $details[$key]['composition_fabric_id'] = $this->request->composition_fabric_id[$key];
            $details[$key]['fabrication'] = NewFabricComposition::find($this->request->composition_fabric_id[$key])->construction;
            $details[$key]['fabric_description'] = $this->request->fabric_description[$key];
            $details[$key]['item_description'] = $this->request->item_description[$key];
            $details[$key]['factory_id'] = Auth::user()->factory_id;
        }
        SampleDetail::insert($details);
    }
}
