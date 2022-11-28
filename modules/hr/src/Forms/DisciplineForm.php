<?php

namespace SkylarkSoft\GoRMG\HR\Forms;


use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use SkylarkSoft\GoRMG\HR\Models\HrDiscipline;

class DisciplineForm extends Form
{

    public function rules()
    {
        $rules = [
            'occurrence_date' => 'required',
            'action_date' => 'required',
            'occurrence_detail' => 'required',
            'investigation_member' => 'required',
            'investigation_detail' => 'required',
            'report_date' => 'required',
            'action_taken' => [
                'required',
                Rule::in(['suspended', 'terminated', 'relief', 'salary_deduction'])
            ],
            'details.*.employee_id' => 'required'
        ];

        if (!$this->input('action_taken')) {
            return $rules;
        }

        if ($this->input('action_taken') === HrDiscipline::ACTION_SALARY_DEDUCTION) {
            return array_merge($rules, [
                'details.*.amount' => 'required',
                'details.*.deduction_month' => 'required'
            ]);
        }

        if ($this->input('action_taken') === HrDiscipline::ACTION_TERMINATED) {
            return array_merge($rules, [
                'details.*.termination_date' => 'required'
            ]);
        }

        if ($this->input('action_taken') === HrDiscipline::ACTION_SUSPENDED) {
            return array_merge($rules, [
                'details.*.suspended_from' => 'required',
                'details.*.suspended_to' => 'required',
            ]);
        }

        return $rules;
    }


    function handle()
    {
        $disciplineData = $this->only(
            'occurrence_date',
            'action_date',
            'occurrence_detail',
            'investigation_member',
            'investigation_detail',
            'report_date',
            'action_taken'
        );

        try {
            $discipline = new HrDiscipline($disciplineData);
            $detailsData = $this->validated()['details'];

            DB::beginTransaction();
            $discipline->save();
            $discipline->details()->createMany($detailsData);
            DB::commit();

            return $discipline;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
