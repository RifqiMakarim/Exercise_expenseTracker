<?php

require __DIR__.'/../models/PengeluaranModel.php';
require __DIR__.'/../models/KategoriModel.php';

class PengeluaranController {
    private $pengeluaranModel;
    private $kategoriModel;

    public function __construct() {
        $this->pengeluaranModel = new PengeluaranModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index() {
        $start = $_GET['start'] ?? date('Y-m-01');
        $end = $_GET['end'] ?? date('Y-m-t');
        
        $data = [
            'pengeluaran' => $this->pengeluaranModel->filterByDate($start, $end),
            'kategori' => $this->kategoriModel->semuaKategori(),
            'total' => $this->hitungTotal(),
            'start' => $start,
            'end' => $end
        ];
        
        $this->tampilkanView('pengeluaran/index', $data);
    }

    // Tampilkan form tambah
    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->prosesTambah();
        }
        $data['kategori'] = $this->kategoriModel->semuaKategori();
        $this->tampilkanView('pengeluaran/tambah', $data);
    }

    private function hitungTotal() {
        $total = 0;
        foreach ($this->pengeluaranModel->semuaPengeluaran() as $p) {
            $total += $p['jumlah'];
        }
        return $total;
    }

    private function validasiInput($input) {
        $errors = [];
        
        if (empty($input['kategori_id'])) {
            $errors[] = "Kategori harus dipilih";
        }
        
        if (empty($input['jumlah']) || $input['jumlah'] <= 0) {
            $errors[] = "Jumlah harus lebih dari 0";
        }
        
        if (empty($input['tanggal'])) {
            $errors[] = "Tanggal harus diisi";
        }
        
        return $errors;
    }
    

    private function prosesTambah() {
        $errors = $this->validasiInput($_POST);
    
        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            header("Location: /tambah");
            exit;
        }

        $data = [
            'kategori_id' => (int)$_POST['kategori_id'],
            'jumlah' => (float)$_POST['jumlah'],
            'deskripsi' => htmlspecialchars($_POST['deskripsi']),
            'tanggal' => $_POST['tanggal']
        ];

        if ($this->pengeluaranModel->tambah($data)) {
            $_SESSION['sukses'] = "Pengeluaran berhasil ditambahkan!";
            header("Location: /");
            exit;
        }
    }

    public function edit($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->prosesEdit();
        }
        
        $data = [
            'kategori' => $this->kategoriModel->semuaKategori(),
            'pengeluaran' => $this->pengeluaranModel->getById($id)
        ];
        
        $this->tampilkanView('pengeluaran/edit', $data);
    }
    
    private function prosesEdit() {
        $data = [
            'id' => (int)$_POST['id'],
            'kategori_id' => (int)$_POST['kategori_id'],
            'jumlah' => (float)$_POST['jumlah'],
            'deskripsi' => htmlspecialchars($_POST['deskripsi']),
            'tanggal' => $_POST['tanggal']
        ];
    
        if ($this->pengeluaranModel->update($data)) {
            $_SESSION['sukses'] = "Pengeluaran berhasil diupdate!";
            header("Location: /");
            exit;
        }
    }

    public function hapus($id) {
        if ($this->pengeluaranModel->hapus($id)) {
            $_SESSION['sukses'] = "Pengeluaran berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus pengeluaran";
        }
        header("Location: /");
        exit;
    }

    private function tampilkanView($view, $data = []) {
        extract($data);
        require __DIR__ . "/../views/{$view}.php";
    }
}
?>