<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\Http\Controllers\UtitlityController;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use SkylarkSoft\GoRMG\Merchandising\Models\Order;
use SkylarkSoft\GoRMG\BasicFinance\Models\Department as AccDepartment;

class User extends Authenticatable implements Auditable
{
    use Notifiable;
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'screen_name',
        'designation',
        'address',
        'phone_no',
        'factory_id',
        'role_id',
        'department',
        'profile_image',
        'signature',
        'permissions',
        'dashboard_version',
        'status',
        'last_login',
        'password',
        'acc_department_id',
    ];

    protected $dates = ['deleted_at'];
    protected $appends = ['full_name_with_email', 'full_name'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    const DASHBOARD_VERSIONS = [
        1 => 'Garments Dashboard',
        2 => 'User Dashboard',
        3 => 'MC Inventory Dashboard',
        //4 => 'PROTRACKER Dashboard',
        5 => 'Approved Dashboard',
    ];

    const GARMENTS_DASHBOARD = 1;
    const USER_DASHBOARD = 2;
    const MC_INVENTORY_DASHBOARD = 3;
    const PROTRACKER_DASHBOARD = 4;
    const APPROVED_DASHBOARD = 5;

    public function generateTags(): array
    {
        return [
            request()->segment(1)
        ];
    }

    public function factory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Factory::class, 'factory_id');
    }

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function departmnt(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class, 'department', 'id');
    }

    public function role_permission(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('SkylarkSoft\GoRMG\SystemSettings\Models\RolePermission', 'department');
    }

    /**
     * get factory wise users for dropdrown
     */
    public static function getUsers($factory_id)
    {
        return self::where('factory_id', $factory_id)
            ->select('first_name', 'last_name', 'screen_name', 'email', 'id')
            ->orderBy('email', 'asc')
            ->get();
    }

    public function getScreenNameAttribute(): ?string
    {
        return $this->attributes['screen_name'] ?? (($this->attributes['first_name'] ?? null) . ' ' . ($this->attributes['last_name'] ?? null));
    }

    public function getFullNameWithEmailAttribute(): string
    {
        if (isset($this->attributes['email'])) {
            $email = ' [' . $this->attributes['email'] . ']';
        } else {
            $email = '';
        }

        if (isset($this->attributes['screen_name']) && isset($this->attributes['first_name']) && isset($this->attributes['last_name'])) {
            $name = ($this->attributes['screen_name']) ? $this->attributes['screen_name'] . $email : $this->attributes['first_name'] . ' ' . $this->attributes['last_name'] . $email;
        } else {
            $name = '' . $email;
        }

        return $name;
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function getFullNameAttribute(): string
    {
        if (isset($this->attributes['first_name'])) {
            $firstName = $this->attributes['first_name'];
        } else {
            $firstName = '';
        }

        if (isset($this->attributes['last_name'])) {
            $lastName = $this->attributes['last_name'];
        } else {
            $lastName = '';
        }

        return $firstName . ' ' . $lastName;
    }

    public function team(): HasOne
    {
        return $this->hasOne(Team::class, 'member_id', 'id')->withDefault();
    }

    public function order(): HasMany
    {
        return $this->hasMany(Order::class, 'created_by');
    }

    public function signature(): HasOne
    {
        return $this->hasOne(ReportSignatureDetail::class, 'username', 'screen_name')->withDefault();
    }
    public function AccDepartment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AccDepartment::class, 'acc_department_id', 'id');
    }
}
