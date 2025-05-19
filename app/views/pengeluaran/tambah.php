<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
    <body>
        <div class="container mt-4">
            <h1 class="mb-4">Expense Tracker</h1>
            <hr>
            <h4 class="mb-4">Tambah Pengeluaran</h4>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        <?php foreach($kategori as $k): ?>
                        <option value="<?= $k['id'] ?>"><?= $k['nama'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" class="form-control" min="0" step="100" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="/" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </body>
</html>


