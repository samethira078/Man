<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Horses extends Model
{
    use HasFactory, HasApiTokens;
    public $timestamps = false;
    protected $fillable = [
        'naam',
        'bio',
        'img',
        'leeftijd',
        'type',
    ];
}
