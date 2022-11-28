<?php


namespace SkylarkSoft\GoRMG\SystemSettings\Models;

use App\FactoryIdTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use FactoryIdTrait;
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public static function countUnreadMessages($receiver_id, $sender_id)
    {
        return self::where([
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'receiver_seen_status' => 0,
        ])->count();
    }
}
