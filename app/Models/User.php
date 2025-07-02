<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    public $table = 'users';
    protected $primaryKey = 'id';

    public function MemberBatch()
    {
        return $this->belongsTo(MemberBatch::class, 'id', 'member_id');
    }

      public function getJWTIdentifier()
    {
        return $this->getKey();
        // return (string) $this->id;
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
