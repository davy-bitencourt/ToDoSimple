<?php
declare(strict_types=1);

ini_set("display_errors", "1");
error_reporting(E_ALL);

spl_autoload_register(function ($class) {
    $prefix = "App\\";
    $baseDir = __DIR__ . "/app/";
    if (strncmp($prefix, $class, strlen($prefix)) !== 0)
        return;
    $relative = substr($class, strlen($prefix));
    $file = $baseDir . str_replace("\\", "/", $relative) . ".php";
    if (is_file($file))
        require $file;
});

use App\Controllers\TaskController;

$method = $_SERVER["REQUEST_METHOD"];
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
if ($path !== "/" && substr($path, -1) === "/")
    $path = rtrim($path, "/");

$controller = new TaskController();

switch (true) {
    case $method === "GET" && $path === "/":
        $controller->index();
        break;

    case $method === "POST" && $path === "/tasks":
        $controller->store($_POST);
        break;

    case $method === "GET" && preg_match("#^/tasks/(\d+)/edit$#", $path, $m):
        $controller->edit((int) $m[1]);
        break;

    case $method === "POST" && preg_match("#^/tasks/(\d+)/update$#", $path, $m):
        $controller->update((int) $m[1], $_POST);
        break;

    case $method === "POST" && preg_match("#^/tasks/(\d+)/toggle$#", $path, $m):
        $controller->toggle((int) $m[1]);
        break;

    case $method === "POST" && preg_match("#^/tasks/(\d+)/delete$#", $path, $m):
        $controller->destroy((int) $m[1]);
        break;

    default:
        http_response_code(404);
        echo "Rota não encontrada";
        break;
}
