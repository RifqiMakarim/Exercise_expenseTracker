<?php
session_start();
require __DIR__ . '/config/database.php';
require __DIR__ . '/app/core/csrf.php';
require __DIR__ . '/app/core/authHelper.php';
require __DIR__ . '/app/controllers/PengeluaranController.php';
require __DIR__ . '/app/controllers/KategoriController.php';
require __DIR__ . '/app/controllers/AuthController.php';
require __DIR__ . '/app/models/UserModel.php';

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_selector']) && isset($_COOKIE['remember_validator'])) {
    $userModel = new UserModel(); 

    $selector = $_COOKIE['remember_selector'];
    $validator_from_cookie = $_COOKIE['remember_validator'];

    $token_data = $userModel->findRememberTokenBySelector($selector);

    if($token_data) {
        $validator_hash_from_db = $token_data['validator_hash'];
        if(hash_equals($validator_hash_from_db,hash('sha256',$validator_from_cookie))) {
            $user = $userModel->findById($token_data['user_id']);
            if ($user) {
              
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];

                $userModel->deleteRememberTokenBySelector($selector);

                $new_selector = bin2hex(random_bytes(16));
                $new_validator = bin2hex(random_bytes(32));
                $new_validator_hash = hash('sha256', $new_validator);
                $expires_seconds = 30 * 24 * 60 * 60;
                $new_expires_at_db = date('Y-m-d H:i:s', time() + $expires_seconds);

                if ($userModel->storeRememberToken($user['id'], $new_selector, $new_validator_hash, $new_expires_at_db)) {
                    setcookie('remember_selector', $new_selector, time() + $expires_seconds, "/", "", false, true);
                    setcookie('remember_validator', $new_validator, time() + $expires_seconds, "/", "", false, true);
                }
            } else {
                $userModel->deleteRememberTokenBySelector($selector);
                setcookie('remember_selector', '', time() - 3600, "/");
                setcookie('remember_validator', '', time() - 3600, "/");
            }
        } else {
            $userModel->deleteRememberTokenBySelector($selector);
            setcookie('remember_selector', '', time() - 3600, "/");
            setcookie('remember_validator', '', time() - 3600, "/");
        }
    } else {
        setcookie('remember_selector', '', time() - 3600, "/");
        setcookie('remember_validator', '', time() - 3600, "/");
    }

}

$pengeluaran_controller = new PengeluaranController();
$kategoriController = new KategoriController();
$authController = new AuthController();

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($request) {
    case '/':
        AuthHelper::requireLogin();
        $pengeluaran_controller->index();
        break;
    case '/tambah':
        AuthHelper::requireLogin();
        $pengeluaran_controller->tambah();
        break;
    case strpos($request, '/edit/') === 0:
        AuthHelper::requireLogin();
        $id = str_replace('/edit/', '', $request);
        $pengeluaran_controller->edit($id);
        break;
    case strpos($request, '/hapus/') === 0:
        AuthHelper::requireLogin();
        $id = str_replace('/hapus/', '', $request);
        $pengeluaran_controller->hapus($id);
        break;

    // Untuk Kategori
    case $request === '/kategori':
        AuthHelper::requireLogin();
        $kategoriController->index();
        break;
    case $request === '/kategori/tambah':
        AuthHelper::requireLogin();
        $kategoriController->tambah();
        break;
    case strpos($request, '/kategori/edit/') === 0:
        AuthHelper::requireLogin();
        $id = str_replace('/kategori/edit/', '', $request);
        $kategoriController->edit($id);
        break;
    case strpos($request, '/kategori/hapus/') === 0:
        AuthHelper::requireLogin();
        $id = str_replace('/kategori/hapus/', '', $request);
        $kategoriController->hapus($id);
        break;


    // Rute untuk Autentikasi
    case '/register':
        AuthHelper::redirectIfAuthenticated();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->processRegistration();
        } else {
            $authController->showRegistrationForm();
        }
        break;

    case '/login':
        AuthHelper::redirectIfAuthenticated();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->processLogin();
        } else {
            $authController->showLoginForm();
        }
        break;

    case '/logout':
        $authController->logout();
        break;

    default:
        http_response_code(404);

        echo "<h3> 404 - Halaman Tidak Ditemukan";

        break;
}
