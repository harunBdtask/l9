<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Strategies\ItemCategoryStrategy;

class ItemCategoryStrategy
{

    private $type;
    private $implementors = [
        'merchandising' => MerchandisingItemCategoriesGenerator::class,
        'general_store' => GeneralStoreItemCategoriesGenerator::class,
        'dyes_store' => DyesStoreItemCategoriesGenerator::class,
    ];

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type): ItemCategoryStrategy
    {
        $this->type = $type;
        return $this;
    }

    public function generate()
    {
        if (!isset($this->implementors[$this->getType()])) {
            return false;
        }

        return (new $this->implementors[$this->getType()])->handle($this);
    }
}
