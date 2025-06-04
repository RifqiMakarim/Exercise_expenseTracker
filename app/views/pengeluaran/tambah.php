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
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php
        $old_input = $_SESSION['old_input'] ?? []; 
        unset($_SESSION['old_input']); 

        // Gunakan old input atau nilai default
        $tanggal_value = $old_input['tanggal'] ?? date('Y-m-d');
        $kategori_id_value = $old_input['kategori_id'] ?? '';
        $jumlah_value = $old_input['jumlah'] ?? '';
        $deskripsi_value = $old_input['deskripsi'] ?? '';
        ?>

        <form method="POST">
            <?= Csrf::csrfField() ?>
            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?= htmlspecialchars($tanggal_value) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select" required>
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= $k['id'] == $kategori_id_value ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah (Rp)</label>
                <input type="number" name="jumlah" class="form-control" value="<?= htmlspecialchars($jumlah_value) ?>" min="0" step="100" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control"><?= htmlspecialchars($deskripsi_value) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="/" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>