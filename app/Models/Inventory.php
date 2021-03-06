<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    public $table = 'inventory';

    public $fillable = [
        'device_type',
        'primary_users',
        'building',
        'floor',
        'room',
        'manufacturer',
        'device_model',
        'mac_address',
        'serial_number',
        'computer_name',
        'drive_info',
        'ram',
        'processor',
        'monitor_count',
        'operating_system',
        'screen_lock_time',
        'antivirus',
        'antivirus_status',
        'comment',
        'date_purchased',
        'is_os_current',
        'is_hd_encrypted',
        'has_user_profiles',
        'requires_password',
        'has_complex_password',
    ];
}
