<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../app/controllers/Controller.php';
require_once __DIR__ . '/../app/controllers/PedidoController.php';
require_once __DIR__ . '/../app/controllers/PratoController.php';
require_once __DIR__ . '/../app/controllers/FuncionarioController.php';

function isLogged(): bool {
    return isset($_SESSION['role']);
}

function isAdmin(): bool {
    return isLogged() && $_SESSION['role'] === 'employee' && !empty($_SESSION['adm']);
}

$r = $_GET['r'] ?? 'pedidos/create';
$parts = explode('/', $r);
$resource = $parts[0] ?? '';
$action = $parts[1] ?? 'index';

switch ($resource) {


    case 'auth':
        if ($action === 'login') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = (int)($_POST['id'] ?? 0);
                $file = __DIR__ . '/../storage/funcionario.json';
                $data = is_file($file) ? json_decode(file_get_contents($file), true) : [];

                foreach ($data as $u) {
                    if (isset($u['id']) && (int)$u['id'] === $id) {
                        $_SESSION['role'] = 'employee';
                        $_SESSION['user'] = $u;
                        $_SESSION['adm'] = !empty($u['adm_access']);
                        header('Location: /?r=pedidos/manage');
                        exit;
                    }
                }

                $_SESSION['login_error'] = 'Funcionário não encontrado';
                include __DIR__ . '/../views/login.php';
                exit;
            }

            include __DIR__ . '/../views/login.php';
            exit;
        }

        if ($action === 'logout') {
            session_destroy();
            header('Location: /?r=pedidos/create');
            exit;
        }
        break;

    case 'pedidos':
        $pc = new PedidoController();

        if ($action === 'create') {
            $pc->create();
        } elseif ($action === 'store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $pc->store($_POST);
        } else {
            if (!isLogged() || ($_SESSION['role'] ?? '') !== 'employee') {
                include __DIR__ . '/../views/login.php';
                exit;
            }

            switch ($action) {
                case 'manage':
                case 'index':
                    $pc->index();
                    break;
                case 'show':
                    if (isset($_GET['id'])) $pc->show((int)$_GET['id']);
                    break;
                case 'update':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') $pc->update((int)($_POST['id'] ?? 0), $_POST);
                    break;
                case 'delete':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') $pc->delete((int)($_POST['id'] ?? 0));
                    break;
                default:
                    $pc->index();
            }
        }
        exit;

    case 'pratos':
    case 'ingredientes':
    case 'igredientes': // compatibilidade antiga
        if (!isAdmin()) {
            include __DIR__ . '/../views/login.php';
            exit;
        }

        $pc = new PratoController();

        switch ($action) {
            case 'manage':
            case 'index':
                $pc->manage();
                break;
            case 'create':
                $pc->create();
                break;
            case 'store':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') $pc->store($_POST);
                break;
            case 'edit':
                if (isset($_GET['id'])) $pc->edit((int)$_GET['id']);
                break;
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') $pc->update((int)($_POST['id'] ?? 0), $_POST);
                break;
            case 'delete':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') $pc->delete((int)($_POST['id'] ?? 0));
                break;
            default:
                $pc->manage();
        }
        exit;

    case 'funcionarios':
        if (!isAdmin()) {
            include __DIR__ . '/../views/login.php';
            exit;
        }

        $fc = new FuncionarioController();

        switch ($action) {
            case 'manage':
            case 'index':
                $fc->index();
                break;
            case 'create':
                $fc->create();
                break;
            case 'store':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') $fc->store($_POST);
                break;
            case 'edit':
                if (isset($_GET['id'])) $fc->edit((int)$_GET['id']);
                break;
            case 'update':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') $fc->update((int)($_POST['id'] ?? 0), $_POST);
                break;
            case 'delete':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') $fc->delete((int)($_POST['id'] ?? 0));
                break;
            default:
                $fc->index();
        }
        exit;
}


include __DIR__ . '/../views/fazerPedido.php';
exit;
