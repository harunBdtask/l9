<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use SkylarkSoft\GoRMG\SystemSettings\Models\NotificationGroup;
use SkylarkSoft\GoRMG\SystemSettings\Models\NotificationSetting;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;

class GroupNotificationUserService
{
    private $type;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): GroupNotificationUserService
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return Builder[]|Collection
     */
    public function get()
    {
        $groupIds = NotificationSetting::query()
            ->where('notification_type', $this->getType())
            ->pluck('receiver_groups')
            ->flatten()
            ->toArray();

        $userIds = [];

        NotificationGroup::query()
            ->whereIn('id', $groupIds)
            ->get()
            ->map(function ($item) use (&$userIds) {
                return collect($item->users)->map(function ($u) use (&$userIds) {
                    $userIds[] = $u;
                });
            });

        $userIds = collect($userIds)->unique()->values()->toArray();

        return User::query()
            ->whereIn('id', $userIds)
            ->get();
    }
}
