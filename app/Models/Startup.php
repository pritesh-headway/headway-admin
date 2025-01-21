<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;

class Startup extends Model
{
    use Notifiable, HasRoles;
    public $table = 'about_startups';
    protected $primaryKey = 'id';
}
