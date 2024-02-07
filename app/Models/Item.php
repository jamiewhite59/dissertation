<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Item extends Model
{
    use HasFactory;
    use HasUuids;

    protected $primaryKey = 'id';

    public function eventItems(): HasMany {
        return $this->hasMany(EventItem::class);
    }

    public function pieces(): HasMany {
        return $this->hasMany(Piece::class);
    }

    public function events(): HasManyThrough {
        return $this->hasManyThrough(Event::class, EventItem::class);
    }
}
