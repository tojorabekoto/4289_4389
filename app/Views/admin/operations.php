<?= $this->extend('admin/layout') ?>

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
        <tr>
          <th>Opération</th>
          <th>Tranche de montant (Ar)</th>
          <th>Frais (Ar)</th>
          <th>Commission autre opérateur (%)</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($tranches)): ?>
          <tr><td colspan="5" class="text-center text-muted py-4">Aucune tranche configurée pour le moment.</td></tr>
        <?php endif; ?>

        <?php foreach ($tranches as $t): ?>
          <form method="post" action="<?= site_url('operateur/operations/modifier/' . $t['id']) ?>">
            <?= csrf_field() ?>
            <tr>
              <td>
                <select name="type_operation_id" class="form-select form-select-sm" required>
                  <?php foreach ($types as $type): ?>
                    <option value="<?= (int) $type['id'] ?>" <?= ((int) $type['id'] === (int) $t['type_operation_id']) ? 'selected' : '' ?>>
                      <?= esc($type['libelle']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </td>
              <td>
                <div class="row g-2">
                  <div class="col-6">
                    <label class="form-label small mb-1">Min</label>
                    <input type="number" name="montant_min" class="form-control form-control-sm" value="<?= esc($t['montant_min']) ?>" required>
                  </div>
                  <div class="col-6">
                    <label class="form-label small mb-1">Max</label>
                    <input type="number" name="montant_max" class="form-control form-control-sm" value="<?= esc($t['montant_max']) ?>" required>
                  </div>
                </div>
              </td>
              <td>
                <input type="number" name="frais" class="form-control form-control-sm" value="<?= esc($t['frais']) ?>" required>
              </td>
              <td>
                <input type="number" name="pourcentage_autre_operateur" class="form-control form-control-sm" value="<?= esc($t['pourcentage_autre_operateur'] ?? 0) ?>" step="0.01" min="0">
              </td>
              <td>
                <div class="d-flex gap-2">
                  <button type="submit" class="btn btn-sm btn-teal">Enregistrer</button>
                  <button type="submit" class="btn btn-sm btn-outline-danger" formaction="<?= site_url('operateur/operations/supprimer/' . $t['id']) ?>" onclick="return confirm('Supprimer cette tranche ?')">Supprimer</button>
                </div>
              </td>
            </tr>
          </form>
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
            <div class="mb-3">
              <label class="form-label">Commission autre opérateur (%)</label>
              <input type="number" name="pourcentage_autre_operateur" class="form-control" placeholder="0" step="0.01" min="0">
              <div class="form-text">S’applique uniquement aux transferts vers un numéro d’un autre opérateur.</div>
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
