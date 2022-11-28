<?php

namespace SkylarkSoft\GoRMG\Merchandising\Services\Booking;

use Illuminate\Database\Eloquent\Model;

abstract class Booking
{
    /**
     * @var Model
     */
    public $model;

    public function save($data): Model
    {
        $this->model->fill($data)->save();
        return $this->model;
    }
}