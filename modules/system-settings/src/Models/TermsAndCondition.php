<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermsAndCondition extends Model
{
    use HasFactory;

    protected $table = 'terms_and_conditions';
    protected $fillable = [
        'page_name',
        'terms_name',
    ];

    const PAGE_NAME = [
//        'main_trims' => 'Main Trims Booking',
//        'short_trims' => 'Short Trims Booking',
//        'short_fabric' => 'Short Fabric Booking',
//        'main_fabric' => 'Main Fabric Booking',
        'proforma_invoice' => 'Proforma Invoice',
        'embellishment' => 'Embellishment WO',
        'service_booking' => 'Service Booking',
        'yarn_purchase_order' => 'Yarn Purchase Order'
    ];
}
