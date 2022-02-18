<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    public function onCards() {
        return $this->belongsToMany(Label::class, "cards_labels");
    }
    public function board() {
        return $this->hasOne(Board::class);
    }
}
