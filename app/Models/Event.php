<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    use HasFactory;
    use HasUuids;

    // customers that belong to the event
    public function customers(): BelongsToMany {
        return $this->belongsToMany(Customer::class, 'event_customer');
    }

    public function eventItems() {
        return DB::table('event_items')
        ->join('items', 'event_items.item_id', '=', 'items.id')
        ->leftJoin('pieces', 'event_items.piece_id', '=', 'pieces.id')
        ->select('event_items.*', 'items.title as item_title', 'items.stock_type as item_stock_type', 'pieces.code as piece_code')
        ->get();
    }

    public function items(): HasManyThrough {
        return $this->hasManyThrough(Item::class, EventItem::class);
    }
}
