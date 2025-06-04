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
        // Ambil old input jika ada
        $old_input = $_SESSION['old_input'] ?? null;
        unset($_SESSION['old_input']); // Hapus setelah diambil

        // Prioritaskan old input, baru kemudian data dari database
        $tanggal_value = $old_input['tanggal'] ?? $pengeluaran['tanggal'];
        $kategori_id_value = $old_input['kategori_id'] ?? $pengeluaran['kategori_id'];
        $jumlah_value = $old_input['jumlah'] ?? $pengeluaran['jumlah'];
        $deskripsi_value = $old_input['deskripsi'] ?? $pengeluaran['deskripsi'];
        ?>

        <form method="POST">
            <?= Csrf::csrfField() ?>
            <input type="hidden" name="id" value="<?= $pengeluaran['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control"
                    value="<?= htmlspecialchars($tanggal_value) ?>" required> {/* Ubah ini */}
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori_id" class="form-select" required>
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= $k['id'] ?>"
                            <?= $k['id'] == $kategori_id_value ? 'selected' : '' ?>> {/* Ubah ini */}
                            <?= htmlspecialchars($k['nama']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah (Rp)</label>
                <input type="number" name="jumlah" class="form-control"
                    value="<?= htmlspecialchars($jumlah_value) ?>" min="0" step="100" required> {/* Ubah ini */}
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control"><?= htmlspecialchars($deskripsi_value) ?></textarea> {/* Ubah ini */}
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="/" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>