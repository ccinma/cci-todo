<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    public function users() {
        return $this->belongsToMany(User::class, "users_workspaces");
    }
    public function creator() {
        return $this->hasOne(User::class);
    }
}
