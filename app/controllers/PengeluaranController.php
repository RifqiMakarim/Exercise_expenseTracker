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
    
    private function hitungTotal() {
        $total = 0;
        foreach ($this->pengeluaranModel->semuaPengeluaran() as $p) {
            $total += $p['jumlah'];
        }
        return $total;
    }
    
    public function tambah() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Permintaan tidak valid atau sesi telah berakhir. Silakan coba lagi.";
                unset($_SESSION['old_input']);
                header("Location: /tambah"); 
                exit;  
            }
            $this->prosesTambah();
        }
        $data['kategori'] = $this->kategoriModel->semuaKategori();
        $this->tampilkanView('pengeluaran/tambah', $data);
    }
    
    private function prosesTambah() {
        $errors = $this->validasiInput($_POST);
    
        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            $_SESSION['old_input'] = $_POST;
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
            unset($_SESSION['old_input']);
            $_SESSION['sukses'] = "Pengeluaran berhasil ditambahkan!";
            header("Location: /");
            exit;
        } else {
            $_SESSION['error'] = "Gagal menambahkan pengeluaran. Silakan coba lagi.";
            $_SESSION['old_input'] = $_POST;
            header("Location: /tambah");
            exit;
        }
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


    public function edit($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isset($_POST['csrf_token']) || !Csrf::validateToken($_POST['csrf_token'])) {
                $_SESSION['error'] = "Permintaan tidak valid atau sesi telah berakhir. Silakan coba lagi.";
                unset($_SESSION['old_input']);
                header("Location: /edit/" . ($_POST['id'] ?? $id));
                exit;
            }
            $this->prosesEdit();
        }
        
        $data = [
            'kategori' => $this->kategoriModel->semuaKategori(),
            'pengeluaran' => $this->pengeluaranModel->getById($id)
        ];

        if (!$data['pengeluaran'] && $_SERVER['REQUEST_METHOD'] !== 'POST') { 
            http_response_code(404);
            echo "Pengeluaran tidak ditemukan.";
            exit;
        }    
        $this->tampilkanView('pengeluaran/edit', $data);
    }

    private function prosesEdit()
    {
        $errors = $this->validasiInput($_POST);

        if (!empty($errors)) {
            $_SESSION['error'] = implode("<br>", $errors);
            // Simpan input lama untuk repopulate form
            $_SESSION['old_input'] = $_POST;
            header("Location: /edit/" . $_POST['id']); // Kembali ke halaman edit
            exit;
        }

        $data = [
            'id' => (int)$_POST['id'],
            'kategori_id' => (int)$_POST['kategori_id'],
            'jumlah' => (float)$_POST['jumlah'],
            'deskripsi' => htmlspecialchars($_POST['deskripsi']),
            'tanggal' => $_POST['tanggal']
        ];

        if ($this->pengeluaranModel->update($data)) {
            unset($_SESSION['old_input']); // Hapus input lama jika sukses
            $_SESSION['sukses'] = "Pengeluaran berhasil diupdate!";
            header("Location: /");
            exit;
        } else {
            // Opsional: Tambahkan pesan error jika update gagal karena alasan lain
            $_SESSION['error'] = "Gagal mengupdate pengeluaran. Silakan coba lagi.";
            $_SESSION['old_input'] = $_POST;
            header("Location: /edit/" . $_POST['id']);
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