<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Workspace extends Model
{
    protected $guarded = [];

    public function users() {
        return $this->belongsToMany(User::class, "users_workspaces");
    }
    public function boards() {
        return $this->hasMany(Board::class);
    }

    public static function validate(Request $request) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
            ],
            'user_id' => [
                'required',
                'numeric',
                'integer',
            ]
        ]);
        return $validated;
    }
}
