<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Event extends Model
{
    use HasFactory;
    use HasUuids;

    // customers that belong to the event
    public function customers(): BelongsToMany {
        return $this->belongsToMany(Customer::class, 'event_customer');
    }

    public function eventItems(): HasMany {
        return $this->hasMany(EventItem::class);
    }

    public function items(): HasManyThrough {
        return $this->hasManyThrough(Item::class, EventItem::class);
    }
}
