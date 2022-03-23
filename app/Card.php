<?php

namespace App;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use Uuids;

    protected $guarded = [];
    
    public function lane() {
        return $this->hasOne(Lane::class, 'id', 'lane_id');
    }
    public function labels() {
        return $this->belongsToMany(Label::class, "cards_labels");
    }
    public function creator() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
