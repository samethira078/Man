<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'adres',
        'nummer',
        'datum',
        'tijd',
        'paard_id',
        'user_id',
        'fullname',
        'betaald',
        'unique'
    ];
}
