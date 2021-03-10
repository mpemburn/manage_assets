<?php

namespace App\Models;

use App\Events\RoleCreated;
use App\Interfaces\UiInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role;

class RoleUi extends Role implements UiInterface
{
    use HasFactory;

    public function save(array $options = [])
    {
        parent::save($options);

        event(new RoleCreated());
    }
}
