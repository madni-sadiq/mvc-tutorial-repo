<?php

use app\core\Application;
use app\controller\SiteController;
use app\controller\AuthController;

require_once __DIR__ . '/vendor/autoload.php';


$app = new Application(__DIR__ );


$app->db->applyMigrations();