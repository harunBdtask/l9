<?php

namespace SkylarkSoft\GoRMG\TimeAndAction\Models;

use App\Casts\Json;
use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use SkylarkSoft\GoRMG\TimeAndAction\Filters\Filter;

class TNATask extends Model
{
    use SoftDeletes;

    protected $table = 'tna_task_entries';

    protected $fillable = [
        'user_id', 'task_name', 'task_short_name', 'task_completion', 'status', 'group_id', 'group_sequence', 'sequence',
        'integration_with_entry_page', 'actual_date_range_calculate', 'connected_task_id', 'plan_date_is_editable', 'lead_time_wise_days', 'created_by',
        'updated_by', 'deleted_by', 'factory_id'
    ];

    protected $casts = [
        'lead_time_wise_days' => Json::class,
    ];

    const TASK_NAMES = [
        'Tech File Receive',
        'Buying Sample Submission',
        'Buying Sample Approval',
        'Trim Card Handover Solid',
        'Trim Card Handover AOP',
        'Trim Card Handover YD',
        'Test Sample Submission',
        'Test Sample Approval',
        'SMS Sample Submission',
        'SMS Sample Approval',
        'CAD - Marker',
        'Price Quotation Confirm Date',
        'Order Receive Date',
        'Photo Sample Requisition',
        'Photo Sample Fabric Booking',
        'Photo Sample Fabric Issue',
        'Photo Sample Making',
        'Photo Sample Submission',
        'Photo Sample Approval',
        'Counter Sample Requisition',
        'Counter Sample Fabric Booking',
        'Counter Sample Submission',
        'Counter Sample Approval',
        'Fit Sample Requisition',
        'Fit Sample Fabric Booking',
        'Fit Sample Fabric Issue',
        'Fit Sample Making',
        'Fit Sample Submission',
        'Fit Sample Approval',
        'Proto Sample Requisition',
        'Proto Sample Fabric Booking',
        'Proto Sample Making',
        'Proto Sample Submission',
        'Proto Sample Approval',
        'Size Set Sample Requisition',
        'Size Set Sample Fabric Booking',
        'Size Set Making Solid',
        'Size Set Making AOP',
        'Size Set Making Y/D',
        'Size Set Sample Fabric Issue',
        'Size Set Sample Making',
        'Size Set Submission Solid',
        'Size Set Submission AOP',
        'Size Set Submission Y/D',
        'Size Set Approval Solid',
        'Size Set Approval AOP',
        'Size Set Approval Y/D',
        'Gold Seal Sample Submission',
        'Gold Seal Sample Approval',
        'Actual Costing Date [Budget]',
        'Fabric Booking Date',
        'Swing Trims Booking',
        'Sewing Trims In-house',
        'Trims Submission',
        'Trims Approval',
        'Trims Booking To Be Issued',
        'Embellishment Booking Solid',
        'Embellishment Booking Aop',
        'Embellishment Booking Y/D',
        'Embellishment Submission',
        'Embellishment Approval',
        'Labdip Requisition',
        'Labdip Receive From Factory',
        'Labdip Submission',
        'Labdip Approval Solid',
        'Labdip Approval AOP',
        'Labdip Approval Y/D',
        'Fabric Sales Order',
        'Yarn Allocating',
        'Yarn purchase order',
        'Yarn purchase requisition',
        'Yarn Receive',
        'Pi Wise Yarn Receive Date',
        'Yarn Send for Dyeing',
        'Dyed Yarn Receive',
        'Yarn Store Requisition',
        'Yarn Issue To Be Done',
        'Yarndip Requisition',
        'Yarndip Approval',
        'Yarndip Submit To Buyer',
        'YD Knit down Approval',
        'YD Knit down Submission',
        'Knitting Plan Solid',
        'Knitting Plan AOP',
        'Knitting Plan YD',
        'Knitting Production Solid/Aop',
        'Knitting production YD',
        'Grey Fabric Delivery To Store',
        'Gray fabric in-house',
        'Grey Fabric Issue',
        'Grey Fabric Requisition for batch',
        'Grey Receive By Batch',
        'Batch Creation',
        'Dyeing Production Solid',
        'Dyeing Production AOP',
        'Dyeing Production YD',
        'Fabric Shrinkage',
        'Packing Accessories Booking',
        'Finishing Accessories In-house',
        'Packing Sample Requisition',
        'Packing Sample Fabric Booking',
        'Packing Sample Fabric Issue',
        'Packing Sample Making',
        'Packing Sample Submission',
        'Packing Sample Approval',
        'Packing Method Approval Date',
        'Packing List Rcv Date',
        'Tag Sample Requisition',
        'Tag Sample Fabric Booking',
        'Tag Sample Fabric Issue',
        'Tag Sample Making',
        'Tag Sample Submission',
        'Tag Sample Approval',
        'Test Sample Approval',
        'Test Sample Submission',
        'Finish Fabric Production Done',
        'Finish fabric Delivery to Store',
        'Finished fabric in-house Solid',
        'Finish Fabrics In-house AOP',
        'Finish Fabrics Inhouse Y/D',
        'Finish Fabric Delivery To Garments',
        'Finish Fabric Issue to Cutting',
        'Trial Production run',
        'Trial cut to be done',
        'Trial production approval after received',
        'Trial production to be submitted',
        'Cutting Production Solid',
        'Cutting Production AOP',
        'Cutting Production Y/D',
        'Garments sent for Print',
        'Garments Receive from Print',
        'Embrodary Sent To Print',
        'Garments Receive from Embrodary',
        'Garments sent for Wash',
        'Garments Receive from Wash',
        'Wash Approval',
        'PP Meeting Solid',
        'PP Meeting AOP',
        'PP Meeting Y/D',
        'PP Sample Requisition',
        'PP Sample Fabric Booking',
        'PP Sample Fabric Issue Solid',
        'PP Sample Fabrics Issue AOP',
        'PP Sample Fabrics Issue Y/D',
        'PP Sample Making Solid',
        'PP Sample Making AOP',
        'PP Sample Submit Solid',
        'PP Sample Submit Aop',
        'PP Sample Submit Y/D',
        'PP Sample Approval Solid',
        'PP Sample Approval AOP',
        'PP Sample Approval Y/D',
        'Production File Handover',
        'Production File Handover Y/D',
        'Internal Communication To Be Done',
        'In Line',
        'In-Line Inspection',
        'Inspection Schedule To Be Offered',
        'Inspection To Be Done',
        'Sewing Input Done',
        'Sewing Production Solid',
        'Sewing Production AOP',
        'Sewing Production Y/D',
        'Production Sample Requisition',
        'Production Sample Fabric Booking',
        'Production Sample Fabric Issue',
        'Production Sample Making',
        'Production Sample Submission',
        'Production Sample Approval',
        'Sample Fabric Booking To Be Issued Knit',
        'Sample Fabric Booking To Be Issued Woven',
        'Pack Finish Date',
        'Iron To Be Done',
        'Ship On Board',
        'Photo In Lay/Litho Link',
        'Pilot Run Review',
        'Poly Entry done',
        'Pre Final',
        'SC/LC Received',
        'Proceeds to be realized',
        'AOP Receive',
        'AOP Strike Off Approval',
        'AOP Strike Off Submission',
        'Bulk Hanger submission',
        'Bulk Hanger Approval',
        'Bulk Swatch Ready Date',
        'Bulk Yarn Approval',
        'Document Dispatched Date',
        'Document to be submited',
        'Documents Mailing',
        'Emb To Be Done',
        'Ex-Factory To Be Done',
        'Export PI Issue',
        'Fabric Booking To Be Issued',
        'Fabric ETA',
        'Fabric ETD',
        'Fabric Send for AOP',
        'Fabric Service Receive',
        'Fabric Service Send AOP',
        'Fabric Service Send YD',
        'Fabric Service Work Order To Be Issued',
        'Fabric Test AOP',
        'Fabric Test To Be Done',
        'Fabric Test YD',
        'Fabric quality sample collection',
        'Final Inspection Booking',
        'Final Sample Approval',
        'Final Sample Fabric Booking',
        'Final Sample Fabric Issue',
        'Final Sample Fabric Requisition',
        'Final Sample Making',
        'Final Sample Submission',
        'First Batch Production',
        'Forwarder Booking',
        'Garments Finishing To Be Done',
        'Garments Handover Date',
        'Garments Test To Be Done',
        'LC Rcv at Bank',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(TNAGroup::class, 'group_id')->withDefault();
    }


    public function connectedTask(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'connected_task_id')->withDefault();
    }

    public function connectedTaskReport(): HasOne
    {
        return $this->hasOne(TNAReports::class, 'task_id', 'connected_task_id');
    }

    public function scopeFilter(Builder $query, Request $request)
    {
        return $query->when($request->get('task_name'), Filter::apply('task_name', $request->get('task_name')))
            ->when($request->get('task_short_name'), Filter::apply('task_short_name', $request->get('task_short_name')))
            ->when($request->get('task_completion'), Filter::apply('task_completion', $request->get('task_completion')))
            ->when($request->get('status'), Filter::apply('status', $request->get('status')))
            ->when($request->get('group_id'), Filter::apply('group_id', $request->get('group_id')))
            ->when($request->get('group_sequence'), Filter::apply('group_sequence', $request->get('group_sequence')));
    }
}
