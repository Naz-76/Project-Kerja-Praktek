<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldSupervisor extends Model
{
    protected $fillable = [
        'department_id',
        'name',
        'position',
        'phone',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
