<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EventItem extends Model
{
    use HasUuids;
    use HasFactory;

    public function item(): HasOne {
        return $this->hasOne(Item::class);
    }

    public function event(): HasOne {
        return $this->hasOne(Event::class);
    }

    public function eventItemPiece(): HasOne {
        return $this->hasOne(EventItemPiece::class);
    }

    public function pieces(): HasManyThrough {
        return $this->hasManyThrough(Piece::class, EventItemPiece::class);
    }
}
