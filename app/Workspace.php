<?php

namespace App;

use App\Traits\Uuids;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Self_;

class Workspace extends Model
{
    use Uuids;

    protected $guarded = [];

    public function members() {
        return $this->belongsToMany(User::class, "users_workspaces")->withPivot('isAdmin');
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
    public function hasMember(User $user, bool $isAdmin = false) : bool
    {
        $member = $this->members->firstWhere('id', $user->id);

        if ( $member ) {
            if ( $isAdmin ) {
                return $member->pivot->isAdmin == "1";
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Add a User to the members of the Workspace
     * 
     * @return void
     */
    public function addMember(User $user, bool $isAdmin = false) : void
    {
        $attributes = [];

        if ( $isAdmin ) {
            $attributes['isAdmin'] = true;
        }

        $this->members()->attach($user->id, $attributes);
    }

    /**
     * Set a new admin for the Workspace. The user must already be a member.
     * 
     * @param User $user The user to set admin
     * 
     * @return bool
     */
    public function setAdmin(User $user) : bool
    {
        if (Self::hasMember($user)) {
            $this->members()->updateExistingPivot($user->id, ['isAdmin' => true]);
            return true;
        }
        return false;
    }
}
