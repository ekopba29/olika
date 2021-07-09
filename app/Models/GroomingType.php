<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroomingType extends Model
{
    protected $fillable  = [
        'grooming_name',
        'price',
        'allow_free'
    ];

    use HasFactory;
    
    public function groomings()
    {
        return $this->hasMany(Grooming::class,"groomingtype_id");
    }
}
