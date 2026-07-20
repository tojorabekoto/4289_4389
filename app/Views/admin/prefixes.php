<?= $this->extend('operateur/layout') ?>

<?= $this->section('contenu') ?>

  <div class="topbar">
    <div>
      <div class="eyebrow">Configuration &amp; supervision</div>
      <h1 class="page-title">Préfixes valables</h1>
      <p class="page-sub">Numéros acceptés lors de la connexion client.</p>
    </div>
  </div>

  <div class="panel">
    <div class="panel-head">
      <div>
        <h2 class="panel-title">Liste des préfixes</h2>
        <p class="panel-desc"><?= count($prefixes) ?> préfixe(s) enregistré(s).</p>
      </div>
      <button class="btn btn-teal" data-bs-toggle="modal" data-bs-target="#modalPrefixe">
        <i class="bi bi-plus-lg me-1"></i>Ajouter un préfixe
      </button>
    </div>

    <table class="mm">
      <thead>
        <tr><th>Préfixe</th><th>Opérateur télécom</th><th>Statut</th><th></th></tr>
      </thead>
      <tbody>
        <?php if (empty($prefixes)): ?>
          <tr><td colspan="4" class="text-center text-muted py-4">Aucun préfixe enregistré pour le moment.</td></tr>
        <?php endif; ?>

        <?php foreach ($prefixes as $p): ?>
          <tr>
            <td class="amount"><?= esc($p['prefixe']) ?></td>
            <td><?= esc($p['description'] ?: '—') ?></td>
            <td>
              <?php if ($p['actif']): ?>
                <span class="pill pill-on"><i class="bi bi-check-circle-fill"></i> Actif</span>
              <?php else: ?>
                <span class="pill pill-off"><i class="bi bi-dash-circle"></i> Désactivé</span>
              <?php endif; ?>
            </td>
            <td class="text-end">
              <a href="<?= site_url('operateur/prefixes/basculer/' . $p['id']) ?>"
                 class="btn btn-sm btn-outline-navy"
                 title="<?= $p['actif'] ? 'Désactiver' : 'Activer' ?>">
                <i class="bi bi-power"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

<?= $this->endSection() ?>


<?= $this->section('modals') ?>

  <div class="modal fade" id="modalPrefixe" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content" style="border-radius:14px;border:none;">
        <form method="post" action="<?= site_url('operateur/prefixes/ajouter') ?>">
          <?= csrf_field() ?>
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">Ajouter un préfixe</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body pt-2">
            <div class="mb-3">
              <label class="form-label">Préfixe</label>
              <input type="text" name="prefixe" class="form-control" placeholder="ex : 034" maxlength="5" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Opérateur télécom</label>
              <input type="text" name="description" class="form-control" placeholder="ex : Airtel">
            </div>
            <div class="form-check form-switch mb-2">
              <input class="form-check-input" type="checkbox" name="actif" value="1" checked id="prefActif">
              <label class="form-check-label" for="prefActif">Actif dès la création</label>
            </div>
          </div>
          <div class="modal-footer border-0 pt-0">
            <button type="button" class="btn btn-outline-navy" data-bs-dismiss="modal">Annuler</button>
            <button type="submit" class="btn btn-teal">Enregistrer</button>
          </div>
        </form>
      </div>
    </div>
  </div>

<?= $this->endSection() ?>
