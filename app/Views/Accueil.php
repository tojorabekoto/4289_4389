<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoraMoney - Accueil</title>
    <link rel="stylesheet" href="<?= base_url('Assets/vendor/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <style>
        body {
            min-height: 100vh;
            background: radial-gradient(circle at top, rgba(15,163,127,.18) 0%, rgba(245,246,249,1) 45%);
        }
        .welcome-card {
            max-width: 540px;
            width: 100%;
            border-radius: 28px;
            box-shadow: 0 24px 64px rgba(15,163,127,.12);
            overflow: hidden;
        }
        .welcome-header {
            font-size: 2rem;
            font-weight: 800;
            color: var(--navy);
        }
        .welcome-sub {
            color: var(--ink-mute);
        }
        .btn-welcome {
            background: var(--teal);
            border-color: var(--teal);
            color: #fff;
            font-weight: 700;
            border-radius: 14px;
        }
        .btn-welcome:hover {
            background: #0c8064;
            border-color: #0c8064;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">

<div class="container">
    <div class="welcome-card bg-white">
        <div class="p-5">
            <h1 class="welcome-header text-center mb-3">Bienvenue sur MoraMoney</h1>
            <p class="text-center welcome-sub mb-4">Créez un compte client rapidement en saisissant votre numéro de téléphone.</p>

            <form method="POST" action="/client/add">
                <div class="mb-4">
                    <label class="form-label">Numéro de téléphone</label>
                    <input
                        type="text"
                        class="form-control form-control-lg"
                        name="numero_telephone"
                        placeholder="Ex. 0341234567"
                        required
                    >
                </div>
                <button class="btn btn-welcome btn-lg w-100">Créer un compte</button>
                <a href="/admin" class="">Admin ></a>
            </form>
        </div>
    </div>
</div>

<script src="<?= base_url('Assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
