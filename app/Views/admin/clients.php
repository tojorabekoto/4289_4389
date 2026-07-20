<?= $this->extend('operateur/layout') ?>

<?= $this->section('contenu') ?>

  <div class="topbar">
    <div>
      <div class="eyebrow">Configuration &amp; supervision</div>
      <h1 class="page-title">Situation des comptes clients</h1>
      <p class="page-sub">Vue d'ensemble des soldes et de l'activité par client.</p>
    </div>
  </div>

  <div class="panel">
    <div class="panel-head">
      <div>
        <h2 class="panel-title">Comptes</h2>
        <p class="panel-desc"><?= count($comptes) ?> compte(s) enregistré(s).</p>
      </div>
      <form method="get" class="input-group input-group-sm" style="width:220px;">
        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
        <input type="text" name="q" class="form-control" placeholder="Rechercher un numéro">
      </form>
    </div>

    <table class="mm">
      <thead>
        <tr><th>Numéro</th><th>Solde actuel</th><th>Nb. transactions</th><th>Statut</th></tr>
      </thead>
      <tbody>
        <?php if (empty($comptes)): ?>
          <tr><td colspan="4" class="text-center text-muted py-4">Aucun compte enregistré pour le moment.</td></tr>
        <?php endif; ?>

        <?php foreach ($comptes as $c): ?>
          <tr>
            <td class="amount"><?= esc($c['numero_telephone']) ?></td>
            <td class="num money-badge">Ar <?= number_format((float) $c['solde'], 0, ',', ' ') ?></td>
            <td class="num"><?= (int) $c['nombre_transactions'] ?></td>
            <td>
              <?php if (($c['statut'] ?? 'actif') === 'actif'): ?>
                <span class="pill pill-on">Actif</span>
              <?php else: ?>
                <span class="pill pill-off">Bloqué</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

<?= $this->endSection() ?>
