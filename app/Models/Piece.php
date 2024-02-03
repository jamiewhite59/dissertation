<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Piece extends Model
{
    use HasFactory;
    use HasUuids;

    public function item(): HasOne {
        return $this->hasOne(Item::class);
    }

    public function eventItemPieces():HasMany {
        return $this->hasMany(EventItemPiece::class);
    }
}
