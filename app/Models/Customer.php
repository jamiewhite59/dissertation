<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Customer extends Model
{
    use HasFactory;
    use HasUuids;

    public $timestamps = false;

    // events that belong to the customer
    public function events(): BelongsToMany {
        return $this->belongsToMany(Event::class, 'event_customer');
    }
}
