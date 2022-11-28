<?php

namespace SkylarkSoft\GoRMG\BasicFinance\Services\Strategies\ItemStrategy;

class ItemStrategy
{
    private $type;
    private $categoryId;
    private $implementors = [
        'merchandising' => MerchandisingItemsGenerator::class,
        'general_store' => GeneralStoreItemsGenerator::class,
        'dyes_store' => DyesStoreItemsGenerator::class,
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
    public function setType($type): ItemStrategy
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * @param $categoryId
     * @return $this
     */
    public function setCategoryId($categoryId): ItemStrategy
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    /**
     * @return false|mixed
     */
    public function generate()
    {
        if (!isset($this->implementors[$this->getType()])) {
            return false;
        }
        return (new $this->implementors[$this->getType()])->handle($this);
    }
}
