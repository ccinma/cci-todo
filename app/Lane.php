<?php

namespace App;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Lane extends Model
{
    use Uuids;

    protected $guarded = [];

    public function board() {
        return $this->hasOne(Board::class, 'id', 'board_id');
    }
    public function cards() {
        return $this->hasMany(Card::class);
    }

    public function creator() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function previous() {
        return $this->hasOne(Self::class, 'id', 'previous_id');
    }
    public function next() {
        return $this->hasOne(Self::class, 'id', 'next_id');
    }
}
