<?php

namespace SkylarkSoft\GoRMG\HR\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EmployeeResourse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'screen_name' => $this->first_name . ' ' . $this->last_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name_bn' => $this->name_bn,
            'department' => $this->employeeOfficialInfo->departmentDetails->name,
            'section' => $this->employeeOfficialInfo->sectionDetails->name,
            'designation' => $this->employeeOfficialInfo->designationDetails->name,
            'grade' => $this->employeeOfficialInfo->grade->name,
            'grade_bn' => $this->employeeOfficialInfo->grade->name_bn,
            'code' => $this->employeeOfficialInfo->code,
            'type' => $this->employeeOfficialInfo->type,
            'nid' => $this->nid,
            'unique_id'=>$this->employeeOfficialInfo->unique_id,
            'punch_card_id'=>$this->employeeOfficialInfo->punch_card_id,
            'date_of_birth' => $this->date_of_birth,
            'father_name' => $this->father_name,
            'father_name_bn' => $this->father_name_bn,
            'mother_name' => $this->mother_name,
            'mother_name_bn' => $this->mother_name_bn,
            'nominee' => $this->nominee,
            'nominee_bn' => $this->nominee_bn,
            'nominee_relation' => $this->nominee_relation,
            'nominee_relation_bn' => $this->nominee_relation_bn,
            'nationality' => $this->nationality,
            'nationality_bn' => $this->nationality_bn,
            'marital_status' => $this->marital_status,
            'present_address' => $this->present_address,
            'present_address_bn' => $this->present_address_bn,
            'blood_group' => $this->blood_group,
            'birth_certificate_no' => $this->birth_certificate_no,
            'acne_details' => $this->acne_details,
            'acne_details_bn' => $this->acne_details_bn,
            'height' => $this->height,
            'lawful_guardian' => $this->lawful_guardian,
            'lawful_guardian_bn' => $this->lawful_guardian_bn,
            'permanent_address' => $this->permanent_address,
            'permanent_address_bn' => $this->permanent_address_bn,
            'zilla' => $this->zilla,
            'zilla_bn' => $this->zilla_bn,
            'upazilla' => $this->upazilla,
            'upazilla_bn' => $this->upazilla_bn,
            'post_code' => $this->post_code,
            'post_code_bn' => $this->post_code_bn,
            'physical_appearance' => $this->physical_appearance,
            'acne' => $this->acne,
            'beard' => $this->beard,
            'mustache' => $this->mustache,
            'bank_info' => $this->bank_info,
            'branch' => $this->branch,
            'account' => $this->account,
            'tin' => $this->tin,
            'basic_salary' => $this->basic_salary,
            'transport_allowance' => $this->transport_allowance,
            'house_rent' => $this->house_rent,
            'medical_allowance' => $this->medical_allowance,
            'food_allowance' => $this->food_allowance,
            'sex' => $this->sex,
            'photo' => is_null($this->document->photo) ? "https://via.placeholder.com/150": asset("/storage/photo/{$this->document->photo}"),
            'signature' => is_null($this->document->signature) ? "https://via.placeholder.com/150" : asset("/storage/signature/{$this->document->signature}"),
            'religion' => $this->religion,
            'emergency_contact_no_bn' => $this->emergency_contact_no_bn,
            'mobile_no' => $this->mobile_no,
            'mobile_no_bn' => $this->mobile_no_bn,
            'salary' => $this->salary,
            'official_info' => $this->officialInfo
        ];
    }
}
