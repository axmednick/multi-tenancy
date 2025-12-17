<?php

namespace Modules\Reports\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Entities\User;

class Report extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getFileUrlAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }

        return 'http://' . tenant('id') . '/storage/tenants/' . $this->file_path;
    }

    public  function user()
    {
        return $this->belongsTo(User::class);
    }

}
