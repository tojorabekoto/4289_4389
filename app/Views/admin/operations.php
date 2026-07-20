<?= $this->extend('operateur/layout') ?>

<?= $this->section('contenu') ?>

  <div class="topbar">
    <div>
      <div class="eyebrow">Configuration &amp; supervision</div>
      <h1 class="page-title">Types d'opérations &amp; barème de frais</h1>
      <p class="page-sub">Frais appliqués par tranche de montant &mdash; modifiable à tout moment.</p>
    </div>
  </div>

  <div class="panel">
    <div class="panel-head">
      <div>
        <h2 class="panel-title">Barème de frais</h2>
        <p class="panel-desc"><?= count($tranches) ?> tranche(s) configurée(s). Le dépôt est gratuit.</p>
      </div>
      <button class="btn btn-teal" data-bs-toggle="modal" data-bs-target="#modalTranche">
        <i class="bi bi-plus-lg me-1"></i>Ajouter une tranche
      </button>
    </div>

    <div class="d-flex gap-2 mb-3 flex-wrap">
      <span class="type-chip"><span class="dot dot-depot"></span>Dépôt <span class="text-muted fw-normal">&middot; gratuit</span></span>
      <span class="type-chip"><span class="dot dot-retrait"></span>Retrait</span>
      <span class="type-chip"><span class="dot dot-transfert"></span>Transfert</span>
    </div>

    <table class="mm">
      <thead>
        <tr><th>Opération</th><th>Tranche de montant (Ar)</th><th>Frais (Ar)</th><th>Statut</th></tr>
      </thead>
      <tbody>
        <?php if (empty($tranches)): ?>
          <tr><td colspan="4" class="text-center text-muted py-4">Aucune tranche configurée pour le moment.</td></tr>
        <?php endif; ?>

        <?php foreach ($tranches as $t): ?>
          <tr>
            <td>
              <span class="type-chip">
                <span class="dot dot-<?= esc($t['code']) ?>"></span><?= esc($t['libelle']) ?>
              </span>
            </td>
            <td class="num text-muted">
              <?= number_format((float) $t['montant_min'], 0, ',', ' ') ?> &ndash;
              <?= number_format((float) $t['montant_max'], 0, ',', ' ') ?>
            </td>
            <td class="num money-badge"><?= number_format((float) $t['frais'], 0, ',', ' ') ?></td>
            <td>
              <?php if ($t['actif']): ?>
                <span class="pill pill-on">Actif</span>
              <?php else: ?>
                <span class="pill pill-off">Désactivé</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

<?= $this->endSection() ?>


<?= $this->section('modals') ?>

  <div class="modal fade" id="modalTranche" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content" style="border-radius:14px;border:none;">
        <form method="post" action="<?= site_url('operateur/operations/ajouter') ?>">
          <?= csrf_field() ?>
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">Ajouter une tranche de frais</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body pt-2">
            <div class="mb-3">
              <label class="form-label">Type d'opération</label>
              <select name="type_operation_id" class="form-select" required>
                <?php foreach ($types as $type): ?>
                  <option value="<?= $type['id'] ?>"><?= esc($type['libelle']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label">Montant min (Ar)</label>
                <input type="number" name="montant_min" class="form-control" placeholder="0" required>
              </div>
              <div class="col-6">
                <label class="form-label">Montant max (Ar)</label>
                <input type="number" name="montant_max" class="form-control" placeholder="0" required>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Frais appliqué (Ar)</label>
              <input type="number" name="frais" class="form-control" placeholder="0" required>
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
