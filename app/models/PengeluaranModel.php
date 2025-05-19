<?php
class PengeluaranModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->conn;
    }

    // Ambil semua pengeluaran dengan nama kategori
    public function semuaPengeluaran() {
        $query = "SELECT p.*, k.nama AS kategori_nama 
                 FROM pengeluaran p
                 JOIN kategori k ON p.kategori_id = k.id
                 ORDER BY p.tanggal DESC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tambah pengeluaran baru
    public function tambah($data) {
        $stmt = $this->db->prepare("INSERT INTO pengeluaran (kategori_id, jumlah, deskripsi, tanggal) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['kategori_id'],
            $data['jumlah'],
            $data['deskripsi'],
            $data['tanggal']
        ]);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM pengeluaran WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($data) {
        $stmt = $this->db->prepare("UPDATE pengeluaran SET 
            kategori_id = ?, 
            jumlah = ?, 
            deskripsi = ?, 
            tanggal = ? 
            WHERE id = ?");
        
        return $stmt->execute([
            $data['kategori_id'],
            $data['jumlah'],
            $data['deskripsi'],
            $data['tanggal'],
            $data['id']
        ]);
    }

    public function hapus($id) {
        $stmt = $this->db->prepare("DELETE FROM pengeluaran WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function filterByDate($start, $end) {
        $stmt = $this->db->prepare("SELECT p.*, k.nama AS kategori_nama 
                                   FROM pengeluaran p
                                   JOIN kategori k ON p.kategori_id = k.id
                                   WHERE p.tanggal BETWEEN ? AND ?
                                   ORDER BY p.tanggal DESC");
        $stmt->execute([$start, $end]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    

}
?>