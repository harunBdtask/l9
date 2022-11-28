<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SkylarkSoft\GoRMG\SystemSettings\Services\ViewPagePermissionService;

class PageWiseViewPermission extends Model
{
    use HasFactory;

    protected $table = "page_wise_view_permissions";
    protected $fillable = ['company_id', 'user_id', 'page_id', 'view_id'];

    public $appends = [
        "page",
        "view"
    ];

    public function getPageAttribute()
    {
        return (new ViewPagePermissionService())->getPageById($this->page_id);
    }

    public function getViewAttribute()
    {
        return (new ViewPagePermissionService())->getViewById($this->view_id);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Factory::class, 'company_id')->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
}
