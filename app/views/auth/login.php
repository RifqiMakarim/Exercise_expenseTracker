<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($judul_halaman ?? 'Login') ?> - Expense Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            max-width: 400px;
            margin-top: 100px;
            margin-bottom: 50px;
        }

        .card-header {
            background-color: #28a745;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container login-container">
        <div class="card shadow-sm">
            <div class="card-header text-center">
                <h2 class="h4 mb-0"><i class="bi bi-box-arrow-in-right"></i> Login ke Akun Anda</h2>
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
                $old_input = $_SESSION['old_input_login'] ?? [];
                unset($_SESSION['old_input_login']); 
                ?>

                <form action="/login" method="POST" class="needs-validation" novalidate>
                    <?= Csrf::csrfField() ?> 

                    <div class="mb-3">
                        <label for="identifier" class="form-label">Username atau Email</label>
                        <input type="text" class="form-control" id="identifier" name="identifier" value="<?= htmlspecialchars($old_input['identifier'] ?? '') ?>" required>
                        <div class="invalid-feedback">Username atau Email harus diisi.</div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="invalid-feedback">Password harus diisi.</div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me" value="1">
                        <label class="form-check-label" for="remember_me">Ingat Saya (Remember Me)</label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-block"><i class="bi bi-door-open-fill"></i> Login</button>
                    </div>
                </form>
                <div class="mt-3 text-center">
                    Belum punya akun? <a href="/register">Daftar di sini</a>
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