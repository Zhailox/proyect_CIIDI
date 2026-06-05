<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Http\Router;

$router = new Router();

// ─── RUTAS DE LA PLATAFORMA ────────────────────────────────

// HOME
$router->get('/', fn() => require __DIR__ . '/../templates/home/index.php');
$router->get('/home', fn() => require __DIR__ . '/../templates/home/index.php');

// INVESTIGACIONES
$router->get('/research', fn() => require __DIR__ . '/../templates/research/index.php');
$router->get('/research/show', fn() => require __DIR__ . '/../templates/research/show.php');
$router->post('/research/show', fn() => require __DIR__ . '/../templates/research/show.php');
$router->get('/projects/create', [\App\Http\Controllers\ProjectController::class, 'create']);
$router->post('/projects/store', [\App\Http\Controllers\ProjectController::class, 'store']);

// EXPERTOS
$router->get('/experts', fn() => require __DIR__ . '/../templates/experts/index.php');

// POSTULACIONES
$router->get('/postulations', fn() => require __DIR__ . '/../templates/postulations/index.php');

// DASHBOARD
$router->get('/dashboard', fn() => require __DIR__ . '/../templates/dashboard/index.php');

// FORO
$router->get('/forum', fn() => require __DIR__ . '/../templates/forum/index.php');
$router->get('/forum/category', fn() => require __DIR__ . '/../templates/forum/category.php');
$router->get('/forum/topic', fn() => require __DIR__ . '/../templates/forum/topic.php');
$router->post('/forum/topic', fn() => require __DIR__ . '/../templates/forum/topic.php');
$router->get('/forum/new', fn() => require __DIR__ . '/../templates/forum/new.php');
$router->post('/forum/new', fn() => require __DIR__ . '/../templates/forum/new.php');

// CURRÍCULO
$router->get('/curriculum/create', [\App\Http\Controllers\CurriculumController::class, 'create']);
$router->post('/curriculum/store', [\App\Http\Controllers\CurriculumController::class, 'store']);

// ─── CAPTURA DEL URI ──────────────────────────────────────
$path   = $_GET['route'] ?? '/';
$path   = '/' . ltrim($path, '/');
$method = $_SERVER['REQUEST_METHOD'];

$scriptName = $_SERVER['SCRIPT_NAME'];
$baseDir    = dirname($scriptName);

define('APP_URL',   $scriptName . '?route=');
define('ASSET_URL', $baseDir);

// ─── DESPACHO ─────────────────────────────────────────────
$router->dispatch($path, $method);
