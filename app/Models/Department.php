<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function slotQuotas()
    {
        return $this->hasMany(SlotQuota::class, 'department_id');
    }

    public function currentQuota($period = null)
    {
        $period = $period ?? date('Y-Q') . ' (' . date('F Y') . ')';
        return $this->slotQuotas()->where('period', $period)->first();
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'department_id');
    }

    public function fieldSupervisors()
    {
        return $this->hasMany(FieldSupervisor::class, 'department_id');
    }
}
