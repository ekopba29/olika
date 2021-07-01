<?php

namespace App\Http\Controllers;

use App\Models\Citie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Addresser extends Controller
{
    public function getAddreser()
    {
        $getter = DB::table('cities')->where('prov_id', 17)
        ->join('districts', 'cities.city_id', '=', 'districts.city_id')
        ->join('subdistricts', 'districts.dis_id', '=', 'subdistricts.dis_id')
        ->get();
        
        foreach($getter as $no => $item){
            $kota[$item->city_id."-". str_replace(" ","_",$item->city_name)][$item->dis_id."-".str_replace(" ","_",$item->dis_name)][] = $item; 
        }

        echo json_encode($kota);
    }
}
