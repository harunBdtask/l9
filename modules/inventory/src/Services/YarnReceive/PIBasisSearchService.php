<?php

namespace SkylarkSoft\GoRMG\Inventory\Services\YarnReceive;

class PIBasisSearchService implements ReceiveBasisSearchContract
{
    private $piNo;
    private $date;

    /**
     * @return mixed
     */
    public function getPiNo()
    {
        return $this->piNo;
    }

    /**
     * @param mixed $piNo
     */
    public function setPiNo($piNo): void
    {
        $this->piNo = $piNo;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    public function format(): array
    {
        return [
            'sl_no' => '1',
            'pi_no' => '2',
            'lc' => 3,
            'date' => 4,
            'supplier' => 5,
            'currency' => 6,
            'source' => 7
        ];
    }
}
