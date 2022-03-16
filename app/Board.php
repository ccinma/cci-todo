<?php

namespace App;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use Uuids;

    protected $guarded = [];
    
    public function creator() {
        return $this->hasOne(User::class);
    }
    public function workspace() {
        return $this->hasOne(Workspace::class);
    }
    public function lanes() {
        return $this->hasMany(Lane::class);
    }
    public function labels() {
        return $this->hasMany(Label::class);
    }
}
