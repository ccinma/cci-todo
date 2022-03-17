<?php

namespace App;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Lane extends Model
{
    use Uuids;

    protected $guarded = [];

    public function board() {
        return $this->hasOne(Board::class);
    }
    public function cards() {
        return $this->hasMany(Card::class);
    }
}
