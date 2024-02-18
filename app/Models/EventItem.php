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

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function item(): HasOne {
        return $this->hasOne(Item::class);
    }

    public function event(): HasOne {
        return $this->hasOne(Event::class);
    }

    public function piece(): HasOne {
        return $this->hasOne(Piece::class);
    }
}
