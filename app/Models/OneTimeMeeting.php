<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class OneTimeMeeting extends Model
{
    use Notifiable, HasRoles;
    public $table = 'one_time_meeting';
    protected $fillable = [
        'name',
        'shop_name',
        'subject',
        'phone_number',
        'message',
        'user_id',
        'status',
        'call_status',
        'schedule_date',
        'schedule_time',
        'remarks'
    ];
    protected $primaryKey = 'id';
}
