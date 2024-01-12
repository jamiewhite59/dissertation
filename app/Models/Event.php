<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;
    use HasUuids;

    // customers that belong to the event
    public function customers(): BelongsToMany {
        return $this->belongsToMany(Customer::class, 'event_customer');
    }
}
