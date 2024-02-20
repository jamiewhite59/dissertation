<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Piece extends Model
{
    use HasFactory;
    use HasUuids;

    public function item(): BelongsTo {
        return $this->belongsTo(Item::class);
    }

    public function eventItems():HasMany {
        return $this->hasMany(EventItem::class);
    }
}
