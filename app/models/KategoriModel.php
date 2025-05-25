<?php
class KategoriModel {
    private $db;
    private $table = 'kategori';

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    public function semuaKategori() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY nama");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function tambah($data) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (nama, deskripsi) VALUES (?, ?)");
        return $stmt->execute([$data['nama'], $data['deskripsi']]);
    }

    public function update($data) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET nama = ?, deskripsi = ? WHERE id = ?");
        return $stmt->execute([$data['nama'], $data['deskripsi'], $data['id']]);
    }

    public function hapus($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            // Tangani error foreign key constraint
            return false;
        }
    }

    public function digunakanDiPengeluaran($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM pengeluaran WHERE kategori_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}
?>