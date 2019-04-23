<?php

require 'vendor/autoload.php';

# Inisialisasi Slim
$app = new \Slim\Slim(array(
    'mode' => 'production'
));

# add middleware Authorization
$app->add(new \Authorization());

# instance chris kacerguis
$random = new \chriskacerguis\Randomstring\Randomstring();

/*
|--------------------------------------------------------------------------
| Route Index
|--------------------------------------------------------------------------
|
|
*/

$app->post('/', function() use ($app)
{
    $app->setName('index');
    echo 'Assalamu\'alaikum';
});

/*
|--------------------------------------------------------------------------
| Route Lokasi
|--------------------------------------------------------------------------
|
|
*/

$app->post('/lokasi(/:page)', function($page = NULL) use ($app, $random)
{
    
    // set limit
    $limitDefault = 10;
    $limit = is_null($app->request->post('limit')) ? $limitDefault : $app->request->post('limit');
    $current_page = $page;
    $allPostVars  = $app->request->post(); // all request post

    $offset = function() use ($current_page, $limit)
    {
        
        $value_offset = is_null($current_page) ? 0 : $limit * ($current_page - 1);
        return $value_offset;
    };

    $lokasi = new \LokasiData($limit, $offset(), $random->generate(8) . time());

    // set response
    $app->response->setStatus(200);
    $app->response->headers->set('Content-Type', 'application/json');
    $app->response->setBody($lokasi->execute($allPostVars));

});

/*
|--------------------------------------------------------------------------
| Route Ustadz
|--------------------------------------------------------------------------
|
|
*/

$app->post('/ustadz(/:page)', function($page = NULL) use ($app, $random)
{

    // set limit
    $limitDefault = 10;
    $limit = is_null($app->request->post('limit')) ? $limitDefault : $app->request->post('limit');
    $current_page = $page;
    $allPostVars  = $app->request->post(); // all request post

    $offset = function() use ($current_page, $limit)
    {
        
        $value_offset = is_null($current_page) ? 0 : $limit * ($current_page - 1);
        return $value_offset;
    };
    
    $ustadz = new \UstadzData($limit, $offset(), $random->generate(8) . time());

    // set response
    $app->response->setStatus(200);
    $app->response->headers->set('Content-Type', 'application/json');
    $app->response->setBody($ustadz->execute($allPostVars));
    
});

/*
|--------------------------------------------------------------------------
| Route Kajian
|--------------------------------------------------------------------------
|
|
*/

$app->post('/kajian(/:page)', function($page = NULL) use ($app, $random)
{

    // set limit
    $limitDefault = 10;
    $limit = is_null($app->request->post('limit')) ? $limitDefault : $app->request->post('limit');
    $current_page = $page;
    $allPostVars  = $app->request->post(); // all request post

    $offset = function() use ($current_page, $limit)
    {
        
        $value_offset = is_null($current_page) ? 0 : $limit * ($current_page - 1);
        return $value_offset;
    };

    $ustadz = new \KajianData($limit, $offset(), $random->generate(8) . time());

    // set response
    $app->response->setStatus(200);
    $app->response->headers->set('Content-Type', 'application/json');
    $app->response->setBody($ustadz->execute($allPostVars));
});

/*
|--------------------------------------------------------------------------
| Mode Production
|--------------------------------------------------------------------------
| Only invoked if mode is "production"
|
*/

$app->configureMode('production', function() use ($app)
{
    $app->config(array(
        'debug' => false
    ));
});

/*
|--------------------------------------------------------------------------
| Mode Development
|--------------------------------------------------------------------------
| Only invoked if mode is "development"
|
*/

$app->configureMode('development', function() use ($app)
{
    $app->config(array(
        'debug' => true
    ));
});

/*
|--------------------------------------------------------------------------
| Not found
|--------------------------------------------------------------------------
| Response if route not found
|
*/

$app->notFound(function() use ($app, $random)
{
    $app->response->setStatus(404);
    $app->response->headers->set('Content-Type', 'application/json');
    echo json_encode(array(
        'seq_id' => $random->generate(8) . time(),
        'status' => 3,
        'message' => 'Route not found'
    ));
});

$app->run();