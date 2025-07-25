<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class EventRequest extends Model
{
    use Notifiable, HasRoles;
    public $table = 'event_request';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'event_id',
    ];

    public function Events()
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }
    public function Users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
