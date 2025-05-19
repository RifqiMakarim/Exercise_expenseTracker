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
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manajemen Kategori</h1>
        <a href="/kategori/tambah" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Kategori
        </a>
    </div>

    <?php if(isset($_SESSION['sukses'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sukses']; unset($_SESSION['sukses']); ?></div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($kategori)): ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-info-circle"></i> Belum ada data kategori
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($kategori as $index => $kat): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($kat['nama']) ?></td>
                            <td><?= htmlspecialchars($kat['deskripsi']) ?></td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="/kategori/edit/<?= $kat['id'] ?>" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="/kategori/hapus/<?= $kat['id'] ?>" class="btn btn-outline-danger" 
                                       onclick="return confirm('Hapus kategori ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-5">
        <a href="/" class="btn btn-secondary">
            <i class="bi bi-chevron-left"></i> Kembali
        </a>
    </div>

</div>
</body>
</html>