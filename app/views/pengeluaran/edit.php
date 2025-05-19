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
        <h4 class="mb-4">Edit Pengeluaran</h4>
        
        <form method="POST">
            <input type="hidden" name="id" value="<?= $pengeluaran['id'] ?>">
            
            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" 
                    value="<?= $pengeluaran['tanggal'] ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select" required>
                    <option value="">Pilih Kategori</option>
                    <?php foreach($kategori as $k): ?>
                    <option value="<?= $k['id'] ?>" 
                        <?= $k['id'] == $pengeluaran['kategori_id'] ? 'selected' : '' ?>>
                        <?= $k['nama'] ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Jumlah (Rp)</label>
                <input type="number" name="jumlah" class="form-control" 
                    value="<?= $pengeluaran['jumlah'] ?>" min="0" step="100" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control"><?= $pengeluaran['deskripsi'] ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="/" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>


