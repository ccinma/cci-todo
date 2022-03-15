<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Workspace extends Model
{
    protected $guarded = [];

    public function members() {
        return $this->belongsToMany(User::class, "users_workspaces");
    }
    public function boards() {
        return $this->hasMany(Board::class);
    }

    /**
     * Return all workspaces the user is a member of or the creator.
     * 
     * @return Collection
     */
    public static function findAllByLoggedUser() : Collection
    {
        $user_id = Auth::user()->id;
        return Self::where(['user_id' => $user_id])->orWhereHas('members', function($q) use($user_id) {
            $q->whereIn('user_id', [$user_id]);
        })->get();
    }

    /**
     * Determine if the User is the creator or a member of the Workspace
     * 
     * @return bool
     */
    public function hasMember(User $user) : bool
    {
        $value = false;
        if ($this->user_id == $user->id) {
            $value = true;
        } else {
            foreach($this->members as $member) {
                if ($member->id == $user->id) {
                    $value = true;
                    break;
                }
            }
        }
        return $value;
    }

    /**
     * Add a User to the members of the Workspace
     * 
     * @return void
     */
    public function addTrustedUser(User $user) : void
    {
        $this->members()->attach($user->id);
    }
}
