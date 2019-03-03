<?php
namespace locations;
require_once 'locations.php';

$app->get('/locations.json', function ($request, $response, $args) {
    \app\validate($args);
    \db\connect();
    extract($request->getQueryParams());
    $who= isset($who)? $who: '';
    $lat= isset($lat)? $lat: '';
    $lon= isset($lon)? $lon: '';
    $bat= isset($bat)? $bat: '';
    $ts_remote= isset($ts_remote)? $ts_remote: '';

    $locations= read($who, $lat, $lon, $bat, $ts_remote);

    return responseSuccess($response, $locations);
});

$app->post('/locations', function ($request, $response, $args) {
    \app\validate($args);
    \db\connect();
    extract($request->getParams());
    $who= isset($who)? $who: '';
    $lat= isset($lat)? $lat: '';
    $lon= isset($lon)? $lon: '';
    $bat= isset($bat)? $bat: '';
    $ts_remote= isset($ts_remote)? $ts_remote: '';

    $id= create($who, $lat, $lon, $bat, $ts_remote);

    return responseSuccess($response, readById($id));
});

?>
