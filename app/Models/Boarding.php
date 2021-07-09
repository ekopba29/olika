<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Boarding extends Model
{
    use HasFactory;

    protected $fillable = [
        'in','out','owner_id','inputter_id','cat_id','freegrooming_used'
    ];

    public function owner()
    {
        return $this->belongsTo(User::class,"owner_id");
    }

    public function inputter()
    {
        return $this->belongsTo(User::class,"inputter_id");
    }

    public function cats()
    {
        return $this->hasMany(Cat::class,"cat_id");
    }
}
