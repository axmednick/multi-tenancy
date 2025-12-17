<?php

namespace Modules\Central\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenantReportSchedule extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'last_executed_at' => 'datetime',
    ];

    public function needsExecution(): bool
    {
        if (!$this->last_executed_at) {
            return true;
        }

        return $this->last_executed_at->addDays(7)->isPast();
    }


}
