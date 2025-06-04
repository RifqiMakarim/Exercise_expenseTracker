<?php

// require_once __DIR__.'/../models/UserModel.php'; 
// require_once __DIR__.'/../core/Csrf.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); 
        }
        $this->userModel = new UserModel();
    }

    public function showRegistrationForm()
    {
        
        $data = [
            'judul_halaman' => 'Registrasi Pengguna Baru'
        ];
        $this->tampilkanView('auth/register', $data);
    }

    public function processRegistration()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
           
            http_response_code(405); 
            $_SESSION['error'] = "Metode tidak diizinkan.";
            header("Location: /register");
            exit;
        }

        if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
            $_SESSION['error'] = "Permintaan tidak valid atau sesi telah berakhir. Silakan coba lagi.";
            unset($_SESSION['old_input']);
            header("Location: /register");
            exit;
        }

        $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

        $_SESSION['old_input'] = $_POST;

        $errors = [];
        if (empty($nama_lengkap)) {
            $errors[] = "Nama lengkap harus diisi.";
        }
        if (empty($username)) {
            $errors[] = "Username harus diisi.";
        } elseif (strlen($username) < 3 || strlen($username) > 50) {
            $errors[] = "Username harus terdiri dari 3-50 karakter.";
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = "Username hanya boleh berisi huruf, angka, dan underscore (_).";
        }

        if (empty($email)) {
            $errors[] = "Email harus diisi.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid.";
        }

        if (empty($password)) {
            $errors[] = "Password harus diisi.";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password minimal harus 6 karakter.";
        }

        if ($password !== $konfirmasi_password) {
            $errors[] = "Konfirmasi password tidak cocok dengan password.";
        }

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            header("Location: /register");
            exit;
        }

        if ($this->userModel->findByUsernameOrEmail($username)) {
            $_SESSION['error'] = "Username sudah digunakan. Silakan pilih username lain.";
            header("Location: /register");
            exit;
        }
        if ($this->userModel->findByUsernameOrEmail($email)) {
            $_SESSION['error'] = "Email sudah terdaftar. Silakan gunakan email lain.";
            header("Location: /register");
            exit;
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $userData = [
            'username' => $username,
            'email' => $email,
            'password_hash' => $password_hash,
            'nama_lengkap' => $nama_lengkap
        ];

        if ($this->userModel->register($userData)) {
            unset($_SESSION['old_input']); // Hapus input lama jika sukses
            $_SESSION['sukses'] = "Registrasi berhasil! Silakan login.";
            header("Location: /login"); // Redirect ke halaman login setelah sukses
            exit;
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat registrasi. Silakan coba lagi.";
            header("Location: /register");
            exit;
        }
    }


    public function showLoginForm()
    {
        if (isset($_SESSION['user_id'])) {
            header("Location: /"); 
            exit;
        }

        $data = [
            'judul_halaman' => 'Login Pengguna'
        ];
        $this->tampilkanView('auth/login', $data);
    }

    public function processLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            $_SESSION['error'] = "Metode tidak diizinkan.";
            header("Location: /login");
            exit;
        }

        if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
            $_SESSION['error'] = "Permintaan tidak valid atau sesi telah berakhir. Silakan coba lagi.";
            unset($_SESSION['old_input_login']);
            header("Location: /login");
            exit;
        }

        $identifier = trim($_POST['identifier'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember_me = isset($_POST['remember_me']);

        $_SESSION['old_input_login'] = ['identifier' => $identifier];

        if (empty($identifier) || empty($password)) {
            $_SESSION['error'] = "Username/Email dan Password harus diisi.";
            header("Location: /login");
            exit;
        }

        $user = $this->userModel->findByUsernameOrEmail($identifier);

        if ($user && password_verify($password, $user['password_hash'])) {
           
            unset($_SESSION['old_input_login']); 
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];

            if ($remember_me) {
                $this->userModel->deleteRememberTokensByUserId($user['id']);

                $selector = bin2hex(random_bytes(16));
                $validator = bin2hex(random_bytes(32));
                $validator_hash = hash('sha256', $validator);
                // 30 hari ( detik)
                $expires_seconds = 30 * 24 * 60 * 60; 
                $expires_at_db = date('Y-m-d H:i:s', time() + $expires_seconds);

                if ($this->userModel->storeRememberToken($user['id'], $selector, $validator_hash, $expires_at_db)) {
                    setcookie('remember_selector', $selector, time() + $expires_seconds, "/", "", false, true); 
                    setcookie('remember_validator', $validator, time() + $expires_seconds, "/", "", false, true); 
                } else {
                    // error_log("Gagal menyimpan remember me token untuk user ID: " . $user['id']);
                }
            } else {
                if (isset($_COOKIE['remember_selector'])) {
                    $this->userModel->deleteRememberTokenBySelector($_COOKIE['remember_selector']);
                    setcookie('remember_selector', '', time() - 3600, "/");
                    setcookie('remember_validator', '', time() - 3600, "/");
                }
            }

            $_SESSION['sukses'] = "Login berhasil! Selamat datang, " . htmlspecialchars($user['nama_lengkap'] ?: $user['username']) . ".";

            $redirect_to = '/'; 
            if (isset($_SESSION['redirect_url'])) {
                $redirect_to = $_SESSION['redirect_url'];
                unset($_SESSION['redirect_url']);
            }
            
            header("Location: " . $redirect_to); 
            exit;
        } else {
            
            $_SESSION['error'] = "Username/Email atau Password salah.";
            header("Location: /login");
            exit;
        }
    }

    public function logout()
    {
        if (isset($_COOKIE['remember_selector'])) {
            $this->userModel->deleteRememberTokenBySelector($_COOKIE['remember_selector']);

            setcookie('remember_selector', '', time() - 3600, "/"); 
            setcookie('remember_validator', '', time() - 3600, "/");
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        header("Location: /login"); 
        exit;
    }


    private function tampilkanView($view, $data = [])
    {
        extract($data);
        $viewPath = __DIR__ . "/../views/{$view}.php";

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            die("Error: File view '{$viewPath}' tidak ditemukan.");
        }
    }
}
