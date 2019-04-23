<?php

require 'vendor/autoload.php';

use Illuminate\Pagination\LengthAwarePaginator as Paginator;

/**
 * LokasiData
 */
class LokasiData
{
    
    private $seq_id;
    private $limit;
    private $offset;
    
    public function __construct($limit, $offset, $seq_id)
    {
        $this->limit  = $limit;
        $this->offset = $offset;
        $this->seq_id = $seq_id;
    }

    public function execute($data)
    {

        $dataLokasi = array();
        $dataArray = array();
        $dataNULL['data'] = array('lokasi' => NULL);

        $findLokasiAll = Lokasi::whereNested(function($query) use ($data)
        {

            // exploding coordinate
            $explodeCoord = explode(",", $data['coordinate']);
                
            $query->where('place', 'LIKE', '%' . $data['place'] . '%');
            $query->where('address', 'LIKE', '%' . $data['address'] . '%');
            $query->whereRaw('calculate_distance_location(latitude, longitude, ' . $explodeCoord[0] .',' . $explodeCoord[1] . ') < ' .$data['distance']);
        })->get();

        $findLokasi = Lokasi::whereNested(function($query) use ($data)
        {

            // exploding coordinate
            $explodeCoord = explode(",", $data['coordinate']);
                
            $query->where('place', 'LIKE', '%' . $data['place'] . '%');
            $query->where('address', 'LIKE', '%' . $data['address'] . '%');
            $query->whereRaw('calculate_distance_location(latitude, longitude, ' . $explodeCoord[0] .',' . $explodeCoord[1] . ') < ' .$data['distance']);
        })->take($this->limit)->offset($this->offset)->get();

        // manually create a new pagination object
        $paging = new Paginator($findLokasi, count($findLokasiAll), $this->limit, $this->offset);

        $i = 0;
        foreach ($findLokasi as $location) {
            $dataArray[$i]['place'] = $location->place;
            $dataArray[$i]['address'] = $location->address;
            $dataArray[$i]['coordinate'] = $location->latitude .',' .$location->longitude;
            $i++;
        }

        $dataLokasi['data'] = array('lokasi' => $dataArray);

        $dataLokasi['pagination'] = array(
                                        'total' => $paging->total(),
                                        'per_page' => $paging->perPage(),
                                        'current_page' => $paging->currentPage(),
                                        'last_page' => $paging->lastPage());

        $content = array(
            'seq_id' => $this->seq_id
        );

        $content['message'] = count($findLokasi) > 0 ? "Data is found" : "Data not found";
        $content['status'] = count($findLokasi) > 0 ? 1 : 2;
        $content = count($findLokasi) > 0 ? array_merge($content, $dataLokasi) : array_merge($content, $dataNULL);

        return json_encode($content);
        
    }
}