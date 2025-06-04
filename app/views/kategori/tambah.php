<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <?php if (isset($_SESSION['error_kategori'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error_kategori'];
                unset($_SESSION['error_kategori']); ?>
            </div>
        <?php endif; ?>

        <?php
        $old_input = $_SESSION['old_kategori_input'] ?? [];
        unset($_SESSION['old_kategori_input']);

        $nama_value = $old_input['nama'] ?? '';
        $deskripsi_value = $old_input['deskripsi'] ?? '';
        ?>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h5 mb-0"><i class="bi bi-plus-circle"></i> Tambah Kategori Baru</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="needs-validation" novalidate>
                            <?= Csrf::csrfField() ?>
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Kategori</label>
                                <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($nama_value) ?>" required>
                                <div class="invalid-feedback">Harap isi nama kategori</div>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" <?= htmlspecialchars($deskripsi_value) ?>></textarea>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="/kategori" class="btn btn-secondary me-md-2">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Validasi form client-side
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');

            Array.from(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>

</html>