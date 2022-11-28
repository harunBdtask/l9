<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Interfaces;

interface CriteriaInterface
{

    public function apply($model, RepositoryInterface $repository);

}
