<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MoraMoney &middot; Back-office opérateur</title>
<link href="<?= base_url('Assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('Assets/vendor/bootstrap/icons/bootstrap-icons.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>

<nav class="sidebar">
  <a href="<?= site_url('operateur/prefixes') ?>" class="brand">
    <span class="brand-mark">MM</span> MoraMoney
  </a>
  <div class="brand-tag">BACK-OFFICE OPÉRATEUR</div>

  <a class="side-link <?= uri_string() === 'operateur/prefixes' ? 'active' : '' ?>"
     href="<?= site_url('operateur/prefixes') ?>">
     <i class="bi bi-sim"></i><span>Préfixes</span>
  </a>
  <a class="side-link <?= uri_string() === 'operateur/operations' ? 'active' : '' ?>"
     href="<?= site_url('operateur/operations') ?>">
     <i class="bi bi-sliders"></i><span>Opérations &amp; frais</span>
  </a>
  <a class="side-link <?= uri_string() === 'operateur/gains' ? 'active' : '' ?>"
     href="<?= site_url('operateur/gains') ?>">
     <i class="bi bi-graph-up-arrow"></i><span>Gains</span>
  </a>
  <a class="side-link <?= uri_string() === 'operateur/clients' ? 'active' : '' ?>"
     href="<?= site_url('operateur/clients') ?>">
     <i class="bi bi-people"></i><span>Comptes clients</span>
  </a>

  <div class="side-foot">Connecté en tant qu'<strong style="color:#C8CEE6">admin</strong><br>Session opérateur</div>
</nav>

<div class="main">

  <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
  <?php endif; ?>

  <?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach (session()->getFlashdata('errors') as $err): ?>
          <li><?= esc($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?= $this->renderSection('contenu') ?>

</div>

<?= $this->renderSection('modals') ?>

<script src="<?= base_url('Assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
