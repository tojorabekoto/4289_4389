<?= $this->extend('admin/layout') ?>

<?= $this->section('contenu') ?>

  <div class="topbar">
    <div>
      <div class="eyebrow">Configuration &amp; supervision</div>
      <h1 class="page-title">Situation des gains</h1>
      <p class="page-sub">Revenus générés par les frais de retrait et de transfert.</p>
    </div>
  </div>

  <div class="panel">

    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="stat-card">
          <div class="stat-label">Gain total</div>
          <div class="stat-value num">Ar <?= number_format($gainTotal, 0, ',', ' ') ?></div>
        </div>
      </div>

      <?php foreach ($gains as $g): ?>
        <div class="col-md-4">
          <div class="stat-card alt">
            <div class="stat-label">Frais <?= esc(strtolower($g['libelle_operation'] ?? $g['type_operation'])) ?>s</div>
            <div class="stat-value num">Ar <?= number_format((float) $g['total_gain'], 0, ',', ' ') ?></div>
            <div class="text-muted small mt-1 num"><?= (int) $g['nombre_operations'] ?> opérations</div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <table class="mm">
      <thead>
        <tr><th>Type d'opération</th><th>Nombre d'opérations</th><th>Total des frais perçus</th><th>Part du gain</th></tr>
      </thead>
      <tbody>
        <?php if (empty($gains)): ?>
          <tr><td colspan="4" class="text-center text-muted py-4">Aucune transaction enregistrée pour le moment.</td></tr>
        <?php endif; ?>

        <?php foreach ($gains as $g):
          $part = $gainTotal > 0 ? round(((float) $g['total_gain'] / $gainTotal) * 100) : 0;
          $type = $g['code'] ?? $g['type_operation'];
        ?>
          <tr>
            <td>
              <span class="type-chip">
                <span class="dot dot-<?= esc($type) ?>"></span><?= esc($g['libelle_operation'] ?? ucfirst($type)) ?>
              </span>
            </td>
            <td class="num"><?= (int) $g['nombre_operations'] ?></td>
            <td class="num amount">Ar <?= number_format((float) $g['total_gain'], 0, ',', ' ') ?></td>
            <td style="width:180px;">
              <div class="progress" style="height:8px;border-radius:6px;">
                <div class="progress-bar" style="width:<?= $part ?>%;background:var(--<?= $type === 'retrait' ? 'amber' : 'teal' ?>);"></div>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

<?= $this->endSection() ?>
