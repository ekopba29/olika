<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'unique_number',
        'phone',
        'level',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function cats()
    {
        return $this->hasMany(Cat::class, "owner_id");
    }

    public function freeGrooming()
    {
        return $this->hasOne(FreeGrooming::class, "owner_id");
    }

    public function listUser($level, $search)
    {
        $get = $this->with('freeGrooming', 'cats')->withCount('cats');
        if ($search["name"] != null) $get->orWhere("users.name",'like','%' .$search["name"] . '%');
        if ($search["email"] != null) $get->orWhere("users.email",'like','%'. $search["email"] . '%');
        if ($search["unique_number"] != null) $get->orWhere("users.unique_number",'like','%'. $search["unique_number"] . '%');
        if ($search["phone"] != null) $get->orWhere("users.phone", 'like','%' .$search["phone"] . '%');
        if ($search["level"] != null) {
            $get->orWhere("users.level", $search["level"]);
        } else {
            $get->whereIn('users.level', $level);
        }
        return $get->paginate(5);
    }

    public function searchUser($search)
    {
        $this->orWhere("users.name", $search["name"]);
        $this->orWhere("users.email", $search["email"]);
        $this->orWhere("users.phone", $search["phone"]);
        return $this->with('freeGrooming', 'cats')->withCount('cats')->paginate(5);
    }

    // public function catsByOwner(){
    //     return $this->with('cats')->simplePaginate(5);
    // }

    // public function grooming()
    // {
    //     return $this->hasMany(FreeGrooming::class,"");
    // }
}
