<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lane extends Model
{
    public function board() {
        return $this->hasOne(Board::class);
    }
    public function cards() {
        return $this->hasMany(Card::class);
    }
}
