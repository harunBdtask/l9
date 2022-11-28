<?php

namespace SkylarkSoft\GoRMG\DyesStore\Interfaces;

interface CriteriaInterface
{
    public function apply($model, RepositoryInterface $repository);
}
