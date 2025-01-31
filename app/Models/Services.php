<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    use Notifiable, HasRoles;
    public $table = 'our_services';
    protected $primaryKey = 'id';
}
