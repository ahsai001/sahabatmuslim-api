<?php

require 'vendor/autoload.php';

use Illuminate\Pagination\LengthAwarePaginator as Paginator;

/**
 * UstadzData
 */
class UstadzData
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

        $dataUstadz = array();
        $dataNULL['data'] = array('ustadz' => NULL);

        $findUstadzAll = Ustadz::whereNested(function($query) use ($data)
        {
                
            $query->where('name', 'LIKE', '%' . $data['name'] . '%');
            $query->where('email', 'LIKE', '%' . $data['email'] . '%');
            $query->where('address', 'LIKE', '%' . $data['address'] . '%');
            $query->where('phone', 'LIKE', '%' . $data['phone'] . '%');
        })->get();

        $findUstadz = Ustadz::whereNested(function($query) use ($data)
        {
                
            $query->where('name', 'LIKE', '%' . $data['name'] . '%');
            $query->where('email', 'LIKE', '%' . $data['email'] . '%');
            $query->where('address', 'LIKE', '%' . $data['address'] . '%');
            $query->where('phone', 'LIKE', '%' . $data['phone'] . '%');
        })->take($this->limit)->offset($this->offset)->get();

        // manually create a new pagination object
        $paging = new Paginator($findUstadz, count($findUstadzAll), $this->limit, $this->offset);

        $dataUstadz['data'] = array('ustadz' => $findUstadz->toArray());

        $dataUstadz['pagination'] = array(
                                        'total' => $paging->total(),
                                        'per_page' => $paging->perPage(),
                                        'current_page' => $paging->currentPage(),
                                        'last_page' => $paging->lastPage());

        $content = array(
            'seq_id' => $this->seq_id
        );

        $content['message'] = count($findUstadz) > 0 ? "Data is found" : "Data not found";
        $content['status'] = count($findUstadz) > 0 ? 1 : 2;
        $content = count($findUstadz) > 0 ? array_merge($content, $dataUstadz) : array_merge($content, $dataNULL);

        return json_encode($content);
    }

}
