<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $table = 'term_and_conditions';
    // Types
    const GENERAL = 1;
    const SPECIAL = 2;

    protected $guarded = [];

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function setTerm($term)
    {
        $this->setAttribute('term', $term);

        return $this;
    }

    public function setType($type)
    {
        $this->setAttribute('type', $type);

        return $this;
    }
}
