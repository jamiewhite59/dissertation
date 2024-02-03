<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class EventItemPiece extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function piece(): HasOne {
        return $this->hasOne(Piece::class);
    }

    public function eventItem(): HasOne {
        return $this->hasOne(EventItem::class);
    }

    public function item(): HasOneThrough {
        return $this->hasOneThrough(Item::class, Piece::class);
    }
}
