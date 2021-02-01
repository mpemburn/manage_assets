<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antivirus extends Model
{
    use HasFactory;

    public $table = 'antiviruses';
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

}
