<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($judul_halaman ?? 'Registrasi') ?> - Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .register-container {
            max-width: 500px;
            margin-top: 50px;
            margin-bottom: 50px;
        }

        .card-header {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container register-container">
        <div class="card shadow-sm">
            <div class="card-header text-center">
                <h2 class="h4 mb-0"><i class="bi bi-person-plus-fill"></i> Registrasi Akun Baru</h2>
            </div>
            <div class="card-body p-4">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['sukses'])):
                ?>
                    <div class="alert alert-success" role="alert">
                        <?= $_SESSION['sukses'];
                        unset($_SESSION['sukses']); ?>
                    </div>
                <?php endif; ?>

                <?php
                $old_input = $_SESSION['old_input'] ?? [];
                unset($_SESSION['old_input']);
                ?>

                <form action="/register" method="POST" class="needs-validation" novalidate>
                    <?= Csrf::csrfField() ?>

                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= htmlspecialchars($old_input['nama_lengkap'] ?? '') ?>" required pattern="^[A-Za-z\s]+$">
                        <div class="invalid-feedback">Nama lengkap harus diisi. (Hanya boleh berisi huruf dan spasi) </div>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($old_input['username'] ?? '') ?>" required minlength="3" maxlength="50" pattern="^[a-zA-Z0-9_]+$">
                        <div class="invalid-feedback">Username harus diisi (3-50 karakter, huruf, angka, atau underscore).</div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($old_input['email'] ?? '') ?>" required>
                        <div class="invalid-feedback">Format email tidak valid.</div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="6">
                        <div class="invalid-feedback">Password minimal 6 karakter.</div>
                    </div>

                    <div class="mb-3">
                        <label for="konfirmasi_password" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" required>
                        <div class="invalid-feedback">Konfirmasi password harus diisi.</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-block"><i class="bi bi-check-circle-fill"></i> Daftar</button>
                    </div>
                </form>
                <div class="mt-3 text-center">
                    Sudah punya akun? <a href="/login">Login di sini</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>

</html>