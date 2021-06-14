<?php
use app\core\Application;
use app\controller\SiteController;
use app\controller\AuthController;

require_once __DIR__. '/../vendor/autoload.php';
$config = [ 'userClass' => \app\models\Users::class];

$app = new Application(__DIR__.'/..', $config);

$app -> router -> get('/', [SiteController::class, 'Home']);
$app -> router -> get('/login',[AuthController::class, 'login']);
$app -> router -> post('/login',[AuthController::class, 'login']);
$app -> router -> get('/register',[AuthController::class, 'register']);
$app -> router -> post('/register',[AuthController::class, 'register']);
$app -> router -> get('/contact',[SiteController::class, 'contact']);
$app -> router -> post('/contact', [SiteController::class, 'contact']);
$app -> router -> get('/logout',[AuthController::class, 'logout']);
$app -> router -> get('/profile',[AuthController::class, 'profile']);
$app->run();