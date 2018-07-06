<?php
namespace logger;

$app->get('/logs.json', function ($request, $response, $args) {
	restrictToKey();
  $queryParams= $request->getQueryParams();
  extract($queryParams);
  $tag= isset($tag)? $tag: '%';
  $level= isset($level)? $level: '%';
  $limit= isset($limit)? $limit: 100;

  $logs= read($tag, $level, $limit);

  return responseSuccess($response, $logs);
});
?>
