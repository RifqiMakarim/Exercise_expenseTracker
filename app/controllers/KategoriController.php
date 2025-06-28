<?php
require_once __DIR__.'/../models/KategoriModel.php';

class KategoriController {
    private $model;

    public function __construct() {
        $this->model = new KategoriModel();
    }

    public function index() {
        $data['kategori'] = $this->model->semuaKategori();
        $this->tampilkanView('kategori/index', $data);
    }

    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
                $_SESSION['error_kategori'] = "Permintaan tidak valid atau sesi telah berakhir. Silakan coba lagi.";
                unset($_SESSION['old_kategori_input']);
                header("Location: /kategori/tambah");
                exit;
            }
            $this->prosesTambah();
        }
        $this->tampilkanView('kategori/tambah');
    }

    private function prosesTambah()
    {
        $errors = $this->validasiInput($_POST);

        if (!empty($errors)) {
            $_SESSION['error_kategori'] = implode("<br>", $errors); // Gunakan key session yang berbeda agar tidak konflik
            $_SESSION['old_kategori_input'] = $_POST;
            header("Location: /kategori/tambah");
            exit;
        }

        $data = [
            'nama' => htmlspecialchars(trim($_POST['nama'])),
            'deskripsi' => htmlspecialchars(trim($_POST['deskripsi']))
        ];

        if ($this->model->tambah($data)) {
            unset($_SESSION['old_kategori_input']);
            $_SESSION['sukses'] = "Kategori berhasil ditambahkan!";
            header("Location: /kategori");
            exit;
        } else {
            $_SESSION['error_kategori'] = "Gagal menambahkan kategori. Silakan coba lagi.";
            $_SESSION['old_kategori_input'] = $_POST;
            header("Location: /kategori/tambah");
            exit;
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
                $_SESSION['error_kategori'] = "Permintaan tidak valid atau sesi telah berakhir. Silakan coba lagi.";
                unset($_SESSION['old_kategori_input']);
                header("Location: /kategori/edit/" . ($_POST['id'] ?? $id));
                exit;
            }   
            $this->prosesEdit();
        }
        
        $data['kategori'] = $this->model->getById($id);
        $this->tampilkanView('kategori/edit', $data);
    }

    private function prosesEdit()
    {
        $errors = $this->validasiInput($_POST);
        $id = (int)$_POST['id']; // Ambil ID untuk redirect jika error

        if (!empty($errors)) {
            $_SESSION['error_kategori'] = implode("<br>", $errors);
            $_SESSION['old_kategori_input'] = $_POST;
            header("Location: /kategori/edit/" . $id);
            exit;
        }

        $data = [
            'id' => $id,
            'nama' => htmlspecialchars(trim($_POST['nama'])),
            'deskripsi' => htmlspecialchars(trim($_POST['deskripsi']))
        ];

        if ($this->model->update($data)) {
            unset($_SESSION['old_kategori_input']);
            $_SESSION['sukses'] = "Kategori berhasil diupdate!";
            header("Location: /kategori");
            exit;
        } else {
            $_SESSION['error_kategori'] = "Gagal mengupdate kategori. Silakan coba lagi.";
            $_SESSION['old_kategori_input'] = $_POST;
            header("Location: /kategori/edit/" . $id);
            exit;
        }
    }

    public function hapus($id) {
        if ($this->model->digunakanDiPengeluaran($id)) {
            $_SESSION['error'] = "Kategori tidak bisa dihapus karena masih digunakan dalam pengeluaran";
        } else {
            if ($this->model->hapus($id)) {
                $_SESSION['sukses'] = "Kategori berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus kategori";
            }
        }
        header("Location: /kategori");
        exit;
    }

    private function validasiInput($input)
    {
        $errors = [];

        if (empty(trim($input['nama']))) {
            $errors[] = "Nama kategori tidak boleh kosong.";
        } elseif (strlen($input['nama']) > 20) { 
            $errors[] = "Nama kategori terlalu panjang (maksimal 20 karakter).";
        }

        if (isset($input['deskripsi']) && strlen($input['deskripsi']) > 1000) {
            $errors[] = "Deskripsi terlalu panjang (maksimal 1000 karakter).";
        }

        return $errors;
    }

    private function tampilkanView($view, $data = []) {
        extract($data);
        require __DIR__."/../views/{$view}.php";
    }
}
?>