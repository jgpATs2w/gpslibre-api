<?php
require 'src/config.php';

$app = new Slim\App($settings);

require 'src/dependencies.php';
include 'src/routes.php';

$app->add(\app\validateMiddleware());

$app->run();
