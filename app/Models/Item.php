<?php

namespace App\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Event;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function events() {
        return Event::whereIn('id', function(Builder $query) {
            $query->select('event_id')->from('event_items')->where('item_id', '=', $this->id);
        })->get();
    }

    public function category(): BelongsTo {
        return $this->belongsTo(Category::class);
    }
}
