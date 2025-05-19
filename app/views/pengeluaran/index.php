<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>
<body>
    <div class="d-flex justify-content-end p-2 me-3">
        <a class="btn btn-primary" href="/kategori">
            <i class="bi bi-tags"></i> Kategori
        </a>
    </div>

    <div class="container mt-4">
        <h1 class="mb-4">Expense Tracker</h1>

        <?php if(isset($_SESSION['sukses'])): ?>
            <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
        <?php endif; ?>
        
        <a href="/tambah" class="btn btn-primary mb-3">Tambah Pengeluaran</a>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Dari Tanggal</label>
                <input type="date" name="start" class="form-control" value="<?= $start ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Sampai Tanggal</label>
                <input type="date" name="end" class="form-control" value="<?= $end ?>">
            </div>
            <div class="col-md-2 align-self-end">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($pengeluaran as $p): ?>
                <tr>
                    <td><?= date('d M Y', strtotime($p['tanggal'])) ?></td>
                    <td><?= $p['kategori_nama'] ?></td>
                    <td>Rp <?= number_format($p['jumlah'], 0, ',', '.') ?></td>
                    <td><?= $p['deskripsi'] ?></td>
                    <td>
                        <a href="/edit/<?= $p['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="/hapus/<?= $p['id'] ?>" class="btn btn-sm btn-danger" 
                        onclick="return confirm('Yakin hapus pengeluaran ini?')">Hapus</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <th>Rp <?= number_format($total, 0, ',', '.') ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>