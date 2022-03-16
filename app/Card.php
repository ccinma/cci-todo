<?php

namespace App;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use Uuids;

    protected $guarded = [];
    
    public function lane() {
        return $this->hasOne(Lane::class);
    }
    public function hasLabels() {
        return $this->belongsToMany(Label::class, "cards_labels");
    }
}
