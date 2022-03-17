<?php

namespace App;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use Uuids;
    
    protected $guarded = [];

    public function onCards() {
        return $this->belongsToMany(Label::class, "cards_labels");
    }
    public function board() {
        return $this->hasOne(Board::class);
    }
}
