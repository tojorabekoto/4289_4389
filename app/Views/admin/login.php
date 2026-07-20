<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoraMoney - Connexion Admin</title>
    <link href="<?= base_url('Assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <style>
        body { display: flex; align-items: center; justify-content: center; min-height: 100vh; background: var(--bg); }
        .login-container { width: 100%; max-width: 400px; }
        .login-card { border: 1px solid var(--line); border-radius: var(--radius); box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .login-header { background: var(--navy); color: #fff; padding: 28px 24px; text-align: center; border-radius: var(--radius) var(--radius) 0 0; }
        .login-header .brand-mark { width: 48px; height: 48px; margin: 0 auto 16px; font-size: 1.2rem; }
        .login-header h1 { font-size: 1.4rem; margin: 0; font-weight: 700; }
        .login-body { padding: 32px 28px; }
        .form-group { margin-bottom: 20px; }
        .form-label { font-weight: 600; font-size: 0.85rem; color: var(--ink); margin-bottom: 8px; display: block; }
        .form-input { width: 100%; padding: 10px 12px; border: 1px solid var(--line); border-radius: 9px; font-size: 0.9rem; box-sizing: border-box; }
        .form-input:focus { border-color: var(--teal); outline: none; box-shadow: 0 0 0 3px var(--teal-soft); }
        .btn-login { background: var(--teal); color: #fff; border: none; padding: 12px 16px; border-radius: 9px; font-weight: 600; width: 100%; cursor: pointer; font-size: 0.9rem; }
        .btn-login:hover { background: #0C8064; }
        .alert { padding: 12px 16px; border-radius: 9px; margin-bottom: 20px; font-size: 0.85rem; }
        .alert-danger { background: #FEE2E2; color: #991B1B; border: 1px solid #FECACA; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="brand-mark" style="background: linear-gradient(135deg, var(--teal), #0C8064); display: flex; align-items: center; justify-content: center;">MM</div>
                <h1>MoraMoney</h1>
                <p style="margin: 8px 0 0; font-size: 0.75rem; color: #B7BFDA; letter-spacing: 0.08em; text-transform: uppercase;">BACK-OFFICE OPÉRATEUR</p>
            </div>
            <div class="login-body">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= esc(session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= site_url('admin/login') ?>">
                    <div class="form-group">
                        <label class="form-label">Identifiant admin</label>
                        <input type="text" name="username" class="form-input" placeholder="Entrez l'identifiant" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-input" placeholder="Entrez le mot de passe" required>
                    </div>

                    <button type="submit" class="btn-login">Se connecter</button>
                </form>

                <p style="text-align: center; margin-top: 16px; font-size: 0.85rem; color: var(--ink-mute);">
                    Seuls les administrateurs peuvent se connecter
                </p>
            </div>
        </div>
    </div>

    <script src="<?= base_url('Assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
