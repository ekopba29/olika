<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    public function subdistrict()
    {
        return $this->hasMany(Subdistrict::class,'subdis_id');
    }

    public function citie()
    {
        return $this->belongsTo(Citie::class,'city_id');
    }
}
