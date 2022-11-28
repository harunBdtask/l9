<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoFileIssueModel extends Model
{
    use HasFactory;

    protected $table = "po_files_issue";
    protected $primaryKey = "id";
    protected $fillable = ["buyer_id", "issue"];

    public function buyer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Buyer::class, 'buyer_id')->withDefault();
    }

    public function scopeFilter($query, $search)
    {
        $query->when($search, function ($query) use ($search) {
            $query->where("issue", "LIKE", "%{$search}%")
                ->orWhereHas('buyer', function ($query) use ($search) {
                    return $query->where("name", "LIKE", "%{$search}%");
                });
        });
    }
}
