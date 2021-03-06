<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_id',
        'name',
        'birth_date',
        'birth_date',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class,"owner_id");
    }

    public function boarding()
    {
        return $this->hasMany(Boarding::class,"cat_id");
    }

    public function grooming()
    {
        return $this->hasMany(Grooming::class);
    }
}
