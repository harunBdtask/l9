<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services;

class PartyTypeService
{
    private function data(): array
    {
        return [
            'Also Notify Party',
            'Buyer',
            'Buyer/Buying Agent',
            'Buyer/Subcontract',
            'Buyer/Supplier',
            'Buying Agent',
            'Client',
            'Consignee',
            'Developing Buyer',
            'Export LC Applicant',
            'LC Applicant/Buying Agent',
            'Notifying Party',
            'Notifying/Consignee',
            'Other Buyer',
            'Subcontract',
        ];
    }

    public function partyTypes(): \Illuminate\Support\Collection
    {
        $data = $this->data();

        return collect($data)->map(function ($party) {
            return [
                'key' => $party,
                'value' => $party,
            ] ;
        })->pluck('value', 'key');
    }
}
