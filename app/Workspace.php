<?php

namespace App;

use App\Traits\Uuids;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Workspace extends Model
{
    use Uuids;

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
    public static function findUserWorkspaces() : Collection
    {
        $user_id = Auth::user()->id;
        return Self::whereHas('members', function($q) use($user_id) {
            $q->whereIn('user_id', [$user_id]);
        })->get();
    }

    /**
     * Determine if the User is the creator of the Workspace
     * 
     * @return bool
     */
    public function isCreator(User $user) : bool
    {
        return $this->user_id == $user->id;
    }

    /**
     * Determine if the User is the creator or a member of the Workspace
     * 
     * @return bool
     */
    public function hasMember(User $user, ?string $role = null) : bool
    {
        $value = false;
        foreach($this->members as $member) {
            if ($member->id == $user->id) {
                if ( $role ) {
                    if ( $member->rôle == $role ) {
                        $value = true;
                    }
                } else {
                    $value = true;
                }
                break;
            }
        }
        return $value;
    }

    /**
     * Add a User to the members of the Workspace
     * 
     * @return void
     */
    public function addMember(User $user) : void
    {
        $this->members()->attach($user->id);
    }
}
