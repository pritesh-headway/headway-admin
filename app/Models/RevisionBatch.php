<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class RevisionBatch extends Model
{
    use Notifiable, HasRoles;
    public $table = 'revision_batch';
    protected $fillable = [
        'plan_id',
        'owner_name',
        'shop_name',
        'phone_number',
        'message',
        'subject',
        'user_id',
        'status',
        'image',
        'revison_batch_status'
    ];
    protected $primaryKey = 'id';

    public function Plans()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }
}
