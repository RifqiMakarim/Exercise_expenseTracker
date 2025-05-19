<?php
require_once __DIR__.'/../models/KategoriModel.php';

class KategoriController {
    private $model;

    public function __construct() {
        $this->model = new KategoriModel();
    }

    // Menampilkan daftar kategori
    public function index() {
        $data['kategori'] = $this->model->semuaKategori();
        $this->tampilkanView('kategori/index', $data);
    }

    // Menampilkan form tambah kategori
    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->prosesTambah();
        }
        $this->tampilkanView('kategori/tambah');
    }

    // Menampilkan form edit kategori
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->prosesEdit();
        }
        
        $data['kategori'] = $this->model->getById($id);
        $this->tampilkanView('kategori/edit', $data);
    }

    // Proses tambah kategori
    private function prosesTambah() {
        $data = [
            'nama' => htmlspecialchars($_POST['nama']),
            'deskripsi' => htmlspecialchars($_POST['deskripsi'])
        ];

        if ($this->model->tambah($data)) {
            $_SESSION['sukses'] = "Kategori berhasil ditambahkan!";
            header("Location: /kategori");
            exit;
        }
    }

    // Proses edit kategori
    private function prosesEdit() {
        $data = [
            'id' => (int)$_POST['id'],
            'nama' => htmlspecialchars($_POST['nama']),
            'deskripsi' => htmlspecialchars($_POST['deskripsi'])
        ];

        if ($this->model->update($data)) {
            $_SESSION['sukses'] = "Kategori berhasil diupdate!";
            header("Location: /kategori");
            exit;
        }
    }

    // Proses hapus kategori
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

    // Method untuk menampilkan view
    private function tampilkanView($view, $data = []) {
        extract($data);
        require __DIR__."/../views/{$view}.php";
    }
}
?>