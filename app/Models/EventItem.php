<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EventItem extends Model
{
    use HasUuids;
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function item(): BelongsTo {
        return $this->belongsTo(Item::class);
    }

    public function event(): BelongsTo {
        return $this->belongsTo(Event::class);
    }

    public function piece(): HasOne {
        return $this->hasOne(Piece::class);
    }
}
