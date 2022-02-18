<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    public function users() {
        return $this->belongsToMany(User::class, "users_workspaces");
    }
    public function boards() {
        return $this->hasMany(Board::class);
    }
}
