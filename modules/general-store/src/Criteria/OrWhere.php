<?php

namespace Skylarksoft\GeneralStore\Criteria;


use Skylarksoft\GeneralStore\Repositories\CriteriaInterface;
use Skylarksoft\GeneralStore\Repositories\RepositoryInterface;

class OrWhere implements CriteriaInterface
{


    private $column;
    private $value;

    public function __construct($column, $value)
    {
        $this->column = $column;
        $this->value = $value;
    }

    /**
     * @param $model
     * @param RepositoryInterface $repository
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        return $model->orWhere($this->column, $this->value);
    }

}
