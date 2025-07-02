<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExibitionVisitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_venue',
        'jeweller_name',
        'owner_name',
        'email',
        'mobile_1',
        'mobile_2',
        'address',
        'city',
        'enquired_for',
        'headway_service',
        'remarks',
    ];
}