<?php

/*header('X_FRAME_OPTIONS', 'ALLOW-FROM http://localhost:4200/');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: *');*/

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check Installation Status
|--------------------------------------------------------------------------
|
| If the application is not yet installed, redirect to the installer.
|
*/

$basePath = dirname(__DIR__);

// Check if installer folder exists inside public
$installerExists = is_dir(__DIR__ . '/installer');

// Current URL path
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$isInstallerRequest = strpos($currentPath, '/installer') === 0;

// 👉 If installer folder exists → force installer
// if ($installerExists && !$isInstallerRequest) {
//     header('Location: /installer');
//     exit;
// }

// 👉 If installer folder NOT exists → block installer and go login
if (!$installerExists && $isInstallerRequest) {
    header('Location: /login');
    exit;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
