<?php

require 'vendor/autoload.php';

use Illuminate\Pagination\LengthAwarePaginator as Paginator;

/**
 * KajianData
 */
class KajianData
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

        if ($data['search_by'] == 'region') {

            $resultKajian = $this->region($data);
            $content = $this->merge_data($resultKajian['findKajian'], $resultKajian['findKajianALL']);

        } elseif ($data['search_by'] == 'coordinate') {
            
            $resultKajian = $this->coordinate($data);
            $content = $this->merge_data($resultKajian['findKajian'], $resultKajian['findKajianALL']);

        } else {

            $content = array(
                'seq_id' => $this->seq_id,
                'status' => 3,
                'message' => 'Type search not found',
                'data' => array('jadwal_kajian' => NULL)
            );
        }

        return json_encode($content);
    }
    
    public function region($data)
    {

        $findKajianALL = Kajian::dataKajian($data['title'], $data['date'], $data['starttime'], $data['endtime'])->with('lokasi', 'ustadz')
                            ->haslokasiaddress($data['place'], $data['address'])
                            ->hasustadz($data['ustadz'])
                            ->count();

        $findKajian = Kajian::dataKajian($data['title'], $data['date'], $data['starttime'], $data['endtime'])->with('lokasi', 'ustadz')
                        ->haslokasiaddress($data['place'], $data['address'])
                        ->hasustadz($data['ustadz'])
                        ->take($this->limit)->offset($this->offset)->get();

        return array('findKajianALL' => $findKajianALL, 'findKajian' => $findKajian);
    }

    public function coordinate($data)
    {

        $findKajianALL = Kajian::dataKajian($data['title'], $data['date'], $data['starttime'], $data['endtime'])->with('lokasi', 'ustadz')
                            ->haslokasicoordinate($data['place'], $data['coordinate'], $data['distance'])
                            ->hasustadz($data['ustadz'])
                            ->count();

        $findKajian = Kajian::dataKajian($data['title'], $data['date'], $data['starttime'], $data['endtime'])->with('lokasi', 'ustadz')
                        ->haslokasicoordinate($data['place'], $data['coordinate'], $data['distance'])
                        ->hasustadz($data['ustadz'])
                        ->take($this->limit)->offset($this->offset)->get();

        return array('findKajianALL' => $findKajianALL, 'findKajian' => $findKajian);
    }

    public function merge_data($findKajian, $findKajianALL)
    {

        $dataKajian = array();
        $dataArray = array();
        $dataNULL['data'] = array('jadwal_kajian' => NULL);

        // manually create a new pagination object
        $paging = new Paginator($findKajian, $findKajianALL, $this->limit, $this->offset);

        $i = 0;
        foreach ($findKajian as $kajian) {
            $dataArray[$i]['title'] = $kajian->title;
            $dataArray[$i]['name'] = $kajian->ustadz->name;
            $dataArray[$i]['place'] = $kajian->lokasi->place;
            $dataArray[$i]['address'] = $kajian->lokasi->address;
            $dataArray[$i]['coordinate'] = $kajian->lokasi->latitude .',' .$kajian->lokasi->longitude;
            $dataArray[$i]['date'] = $kajian->tanggal;
            $dataArray[$i]['starttime'] = $kajian->starttime;
            $dataArray[$i]['endtime'] = $kajian->endtime;
        $i++;
        }

        $dataKajian['data'] = array('jadwal_kajian' => $dataArray);

        $dataKajian['pagination'] = array(
                                        'total' => $paging->total(),
                                        'per_page' => $paging->perPage(),
                                        //'current_page' => $paging->currentPage(),
                                        'current_page' => $this->offset,
                                        'last_page' => $paging->lastPage());

        $content = array(
            'seq_id' => $this->seq_id
        );

        $content['message'] = count($findKajian) > 0 ? "Data is found" : "Data not found";
        $content['status'] = count($findKajian) > 0 ? 1 : 2;
        $content = count($findKajian) > 0 ? array_merge($content, $dataKajian) : array_merge($content, $dataNULL);

        return $content;
    }
    
}