<?php
/**
 * Created by PhpStorm.
 * User: siam
 * Date: 8/19/19
 * Time: 11:51 AM
 */

namespace SkylarkSoft\GoRMG\Merchandising\Handle\Sample;

use SkylarkSoft\GoRMG\Merchandising\Models\SampleDetail;

class SampleDetailsData
{
    private $sample_id;

    public function __construct($sample_id)
    {
        $this->sample_id = $sample_id;
    }

    public function get()
    {
        $sample_developments_details = SampleDetail::with('item', 'fabrication')->where('sample_id', $this->sample_id)->get();
        $html = '';
        foreach ($sample_developments_details as $details) {
            $html .= '<tr>
                        <td>' . $details->item->name . '</td>
                        <td>' . $details->item_description . '</td>
                        <td>' . $details->fabric_description . '</td>
                        <td>' . $details->gsm . '</td>
                        <td>' . $details->unit_price . '</td>
                      </tr>';
        }
        echo $html;
        exit;
    }
}
