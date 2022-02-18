<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public function lane() {
        return $this->hasOne(Lane::class);
    }
    public function hasLabels() {
        return $this->belongsToMany(Label::class, "cards_labels");
    }
}
