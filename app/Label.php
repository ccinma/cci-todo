<?php

namespace App;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use Uuids;
    
    protected $guarded = [];

    public function cards() {
        return $this->belongsToMany(Card::class, "cards_labels");
    }
    public function board() {
        return $this->hasOne(Board::class, 'id', 'board_id');
    }
    public function creator() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
