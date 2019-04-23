<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Kajian extends Eloquent
{
    
    protected $table = 'kajian';
    
    /*
     * Relasi One-to-Many
     * =================
     * model 'Kajian' memiliki relasi One-to-Many (belongsTo) sebagai penerima 'ustadz_id'
     */
    public function ustadz()
    {
        return $this->belongsTo('Ustadz', 'ustadz_id');
    }
    
    /*
     * Relasi One-to-Many
     * =================
     * model 'Kajian' memiliki relasi One-to-Many (belongsTo) sebagai penerima 'lokasi_id'
     */
    public function lokasi()
    {
        return $this->belongsTo('Lokasi', 'lokasi_id');
    }
    
    /*
     * Relasi One-to-Many
     * =================
     * model 'Kajian' memiliki relasi One-to-Many (belongsTo) sebagai penerima 'user_id'
     */
    public function user()
    {
        return $this->belongsTo('User', 'user_id');
    }
    
    public function scopeDataKajian($query, $title, $date, $starttime, $endtime)
    {
        return $query;
        /*           
        return $query->where('title', 'like', '%' . $title . '%')
        			 ->where('tanggal', 'like', '%' . $date . '%')
                     ->whereRaw("((ADDTIME('$starttime','00:01:00') BETWEEN starttime AND endtime) OR (starttime BETWEEN ADDTIME('$starttime','00:01:00') AND SUBTIME('$endtime','00:01:00')))");
                     */
    }

    public function scopeHasLokasiAddress($query, $place, $address)
    {

        return $query->whereHas('lokasi', function ($q) use ($place, $address) 
        {
            //$q->where('lokasi.place', 'LIKE', '%' . $place . '%');
            $q->where('lokasi.address', 'LIKE', '%' . $address . '%');
        });

    }

    public function scopeHasLokasiCoordinate($query, $place, $coordinate, $distance)
    {

        // exploding coordinate
        $explodeCoord = explode(",", $coordinate);
        $explodeCoordLatitude = $explodeCoord[0];
        $explodeCoordLongitude = $explodeCoord[1];

        return $query->whereHas('lokasi', function ($q) use ($place, $explodeCoordLatitude, $explodeCoordLongitude, $distance) 
        {
            //$q->where('lokasi.place', 'LIKE', '%' . $place . '%');
            $q->whereRaw('calculate_distance_location(latitude, longitude, ' . $explodeCoordLatitude .',' . $explodeCoordLongitude . ') < ' .$distance);
        });

    }

    public function scopeHasUstadz($query, $ustadz)
    {
        return $query;
        /*           
        return $query->whereHas('ustadz', function ($q) use ($ustadz) 
        {
            $q->where('ustadz.name', 'LIKE', '%' . $ustadz . '%');
        });
        */
    }
    
}