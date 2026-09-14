<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotQuota extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'period',
        'quota_total',
        'quota_used',
    ];

    protected $appends = ['quota_remaining'];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function getQuotaRemainingAttribute(): int
    {
        return max(0, $this->quota_total - $this->quota_used);
    }
}
