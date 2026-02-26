<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'formatted_address', 'latitude', 'longitude',
        'ne_latitude', 'ne_longitude', 'sw_latitude', 'sw_longitude'
    ];

    // public function province()
    // {
    //     return $this->belongsTo(Area::class, DB::raw('LEFT(code, 2)'), 'code');
    // }

    static function getProvinces()
    {
        return DB::table('areas')
        ->whereRaw('CHAR_LENGTH(code)=2')
        ->orderBy('areas.code')
        ->get();
    }

    static function getCities($arg)
    {
        $area = DB::table('areas');

        if(!empty($arg['province_id'])){$area->where('province.id', $arg['province_id']);}

        $area->select('areas.id as id', 'areas.name')
        ->join('areas AS province', 'province.code', '=', DB::raw('LEFT(areas.code, 2)'))
        ->whereRaw('CHAR_LENGTH(areas.code)=5')
        ->orderBy('areas.name');

        return $area->get();
    }

    static function getDistricts($arg)
    {
        $area = DB::table('areas');

        if(!empty($arg['province_id'])){$area->where('province.id', $arg['province_id']);}
        if(!empty($arg['city_id'])){$area->where('city.id', $arg['city_id']);}

        $area->selectRaw('areas.id as id, CONCAT(\'Kec. \', areas.name) as name')
        ->join('areas AS city', 'city.code', '=', DB::raw('LEFT(areas.code, 5)'))
        ->join('areas AS province', 'province.code', '=', DB::raw('LEFT(areas.code, 2)'))
        ->whereRaw('CHAR_LENGTH(areas.code)=8')
        ->orderBy('areas.name');

        return $area->get();
    }

    static function getVillages($arg)
    {
        $area = DB::table('areas');

        if(!empty($arg['province_id'])){$area->where('province.id', $arg['province_id']);}
        if(!empty($arg['city_id'])){$area->where('city.id', $arg['city_id']);}
        if(!empty($arg['district_id'])){$area->where('district.id', $arg['district_id']);}

        $area->selectRaW('areas.id as id, (CASE SUBSTRING(areas.code, 10, 1) WHEN \'1\' THEN CONCAT(\'Kel. \', areas.name) ELSE CONCAT(\'Ds. \', areas.name) END) as name')
        ->join('areas AS district', 'district.code', '=', DB::raw('LEFT(areas.code, 8)'))
        ->join('areas AS city', 'city.code', '=', DB::raw('LEFT(areas.code, 5)'))
        ->join('areas AS province', 'province.code', '=', DB::raw('LEFT(areas.code, 2)'))
        ->whereRaw('CHAR_LENGTH(areas.code)=13')
        ->orderByRaw('SUBSTRING(areas.code, 10, 1) DESC, areas.name ASC');

        return $area->get();
    }

    function getVillageAttribute() {
        $area = DB::table('areas');

        $area->selectRaw('areas.code as id, (CASE SUBSTRING(areas.code, 10, 1) WHEN \'1\' THEN CONCAT(\'Kel. \', areas.name) ELSE CONCAT(\'Ds. \', areas.name) END) as name')
        ->whereRaw('code=\''.$this->code.'\'');

        return $area->first();
    }

    function getDistrictAttribute() {
        $area = DB::table('areas');

        $area->selectRaw('areas.code as id, CONCAT(\'Kec. \', areas.name) as name')
        ->whereRaw('CHAR_LENGTH(areas.code)=8')
        ->whereRaw('code=SUBSTRING(\''.$this->code.'\', 1, 8)');

        return $area->first();
    }

    function getCityAttribute() {
        $area = DB::table('areas');

        $area->selectRaw('areas.code as id, areas.name')
        ->whereRaw('CHAR_LENGTH(areas.code)=5')
        ->whereRaw('code=SUBSTRING(\''.$this->code.'\', 1, 5)');

        return $area->first();
    }

    function getProvinceAttribute() {
        $area = DB::table('areas');

        $area->selectRaw('areas.code as id, areas.name')
        ->whereRaw('CHAR_LENGTH(areas.code)=2')
        ->whereRaw('code=SUBSTRING(\''.$this->code.'\', 1, 2)');

        return $area->first();
    }
}