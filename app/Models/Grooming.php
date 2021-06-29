<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grooming extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_id',
        'cat_id',
        'groomer_id',
        'payment',
        'inputer_id',
        'grooming_at',
        'accumulated_free_grooming'
    ];

    public function owner () {
        return $this->belongsTo(User::class,"owner_id");
    }

    public function groomer () {
        return $this->belongsTo(User::class,"groomer_id");
    }

    public function inputer () {
        return $this->belongsTo(User::class,"inputer_id");
    }

    public function cat () {
        return $this->belongsTo(Cat::class);
    }

}
