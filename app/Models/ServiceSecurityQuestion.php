<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSecurityQuestion extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'service _id',
        'question',
        'answer'
    ];

}
