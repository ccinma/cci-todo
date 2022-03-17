<?php

namespace App;

use App\Traits\Uuids;
use Auth;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use Uuids;

    protected $guarded = [];
    
    public function creator() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function workspace() {
        return $this->hasOne(Workspace::class, 'id', 'workspace_id');
    }
    public function lanes() {
        return $this->hasMany(Lane::class);
    }
    public function labels() {
        return $this->hasMany(Label::class);
    }

    public function isCreator(User $user) : bool
    {
        return $this->user_id == $user->id;
    }
}
