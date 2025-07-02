<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo',
        'title',
        'desc',
        'play_store',
        'app_store',
        'web_url',
        'tagline',
        'product_banner',
    ];
}
