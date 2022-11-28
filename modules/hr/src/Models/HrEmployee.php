<?php

namespace SkylarkSoft\GoRMG\HR\Models;

use App\ModelCommonTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class HrEmployee extends Model
{
    use SoftDeletes, ModelCommonTrait;

    const STAFF = 'staff';
    const WORKER = 'worker';
    const MANAGEMENT = 'management';

    protected $table = 'hr_employees';

    protected $fillable = [
        'unique_id',
        'first_name',
        'last_name',
        'name_bn',
        'department',
        'section',
        'designation',
        'code',
        'type',
        'nid',
        'date_of_birth',
        'father_name',
        'father_name_bn',
        'mother_name',
        'mother_name_bn',
        'nominee',
        'nominee_bn',
        'nominee_relation',
        'nominee_relation_bn',
        'emergency_contact_no_bn',
        'mobile_no',
        'mobile_no_bn',
        'spouse_name',
        'spouse_name_bn',
        'nationality',
        'nationality_bn',
        'marital_status',
        'present_address',
        'present_address_bn',
        'blood_group',
        'birth_certificate_no',
        'acne_details',
        'acne_details_bn',
        'height',
        'lawful_guardian',
        'lawful_guardian_bn',
        'religion',
        'religion_bn',
        'permanent_address',
        'permanent_address_bn',
        'zilla_id',
        'zilla_bn_id',
        'upazilla_id',
        'upazilla_bn_id',
        'physical_appearance',
        'acne',
        'beard',
        'mustache',
        'bank_info',
        'branch',
        'account',
        'tin',
        'basic_salary',
        'transport_allowance',
        'house_rent',
        'medical_allowance',
        'food_allowance',
        'sex',
        'photo',
        'reference_id',
        'source',
        'factory_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'village',
        'village_bn',
        'present_address_zilla_id',
        'present_address_zilla_bn_id',
        'present_address_village',
        'present_address_village_bn',
        'present_address_upazilla_id',
        'present_address_upazilla_bn_id',
        'employee_id',
        'machine_id',
        'post_office_id',
        'post_office_bn',
        'post_code_id',
        'post_code_bn',
        'present_address_post_office_id',
        'present_address_post_office_bn',
        'present_address_post_code_id',
        'present_address_post_code_bn',
    ];

    protected $appends = ['screen_name'];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getScreenNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function departmentDetails(): BelongsTo
    {
        return $this->belongsTo(HrDepartment::class, 'department', 'id')->withDefault();
    }

    public function designationDetails(): BelongsTo
    {
        return $this->belongsTo(HrDesignation::class, 'designation', 'id')->withDefault();
    }

    public function sectionDetails(): BelongsTo
    {
        return $this->belongsTo(HrSection::class, 'section', 'id')->withDefault();
    }

    public function educations(): HasMany
    {
        return $this->hasMany(HrEmployeeEducationInfo::class, 'employee_id');
    }

    public function jobExperiences(): HasMany
    {
        return $this->hasMany(HrEmployeeJobExperience::class, 'employee_id');
    }

    public function salary(): HasOne
    {
        return $this->hasOne(HrEmployeeSalaryInfo::class, 'employee_id')->withDefault();
    }

    public function document(): HasOne
    {
        return $this->hasOne(HrEmployeeDocument::class, 'employee_id')->withDefault();
    }

    public function employeeOfficialInfo(): HasOne
    {
        return $this->hasOne(HrEmployeeOfficialInfo::class, 'employee_id')->withDefault();
    }

    public function officialInfo(): HasOne
    {
        return $this->hasOne(HrEmployeeOfficialInfo::class, 'employee_id')->withDefault();
    }

    public function zilla(): BelongsTo
    {
        return $this->belongsTo(HrZilla::class, 'zilla_bn_id', 'id')->withDefault();
    }

    public function presentAdressZilla(): BelongsTo
    {
        return $this->belongsTo(HrZilla::class, 'present_address_zilla_bn_id', 'id')->withDefault();
    }

    public function upazilla(): BelongsTo
    {
        return $this->belongsTo(HrUpazilla::class, 'upazilla_bn_id', 'id')->withDefault();
    }

    public function presentAdressUpazilla(): BelongsTo
    {
        return $this->belongsTo(HrUpazilla::class, 'present_address_upazilla_bn_id', 'id')->withDefault();
    }

    public function presentAdressPostOffice(): BelongsTo
    {
        return $this->belongsTo(HrPostOffice::class, 'present_address_post_office_id', 'id')->withDefault();
    }

    public function postOfice(): BelongsTo
    {
        return $this->belongsTo(HrPostOffice::class, 'post_office_id', 'id')->withDefault();
    }

    public function termination(): HasOne
    {
        return $this->hasOne(HrTermination::class, 'employee_id', 'id')->withDefault();
    }

}
