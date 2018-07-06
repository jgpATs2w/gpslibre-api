<?php
namespace locations;
require_once 'locations.php';

$app->get('/locations.json', function ($request, $response, $args) {
    \app\validate($args);
    explode($request->getQueryParams());
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
    explode($request->getParams());
    $who= isset($who)? $who: '';
    $lat= isset($lat)? $lat: '';
    $lon= isset($lon)? $lon: '';
    $bat= isset($bat)? $bat: '';
    $ts_remote= isset($ts_remote)? $ts_remote: '';

    create($who, $lat, $lon, $bat, $ts_remote);

    return responseSuccess();
});

?>
