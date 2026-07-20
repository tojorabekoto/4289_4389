<body class="bg-light">
<link rel="stylesheet" href="/Assets/vendor/bootstrap/css/bootstrap.min.css">

<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <!-- Messages -->
            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= esc($success) ?>
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= esc($error) ?>
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($successop)): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= esc($successop) ?>
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($errorop)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= esc($errorop) ?>
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">

                    <h2 class="fw-bold text-primary mb-4">
                        Welcome <?= esc($data['numero_telephone']) ?>!
                    </h2>

                    <div class="bg-light rounded-3 p-3 mb-4">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Phone Number</span>
                            <strong><?= esc($data['numero_telephone']) ?></strong>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Current Balance</span>
                            <span class="badge bg-success fs-6">
                                <?= number_format($solde, 0, ',', ' ') ?> Ar
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="/client/operation">

                        <input
                            type="hidden"
                            name="numero_telephone"
                            value="<?= esc($data['numero_telephone']) ?>"
                        >

                        <div class="mb-3">
                            <label class="form-label">
                                Operation Type
                            </label>

                            <select
                                name="type_operation"
                                id="type_operation"
                                class="form-select"
                                required
                            >
                                <option value="">Choose an operation</option>

                                <?php foreach ($types_operation as $type_operation): ?>
                                    <option value="<?= esc($type_operation['code']) ?>">
                                        <?= esc(ucfirst($type_operation['code'])) ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Amount
                            </label>

                            <div class="input-group">
                                <input
                                    type="number"
                                    name="montant"
                                    class="form-control"
                                    placeholder="Enter amount"
                                    required
                                >
                                <span class="input-group-text">Ar</span>
                            </div>
                        </div>

                        <div
                            id="transfer-recipient-field"
                            class="mb-3"
                            style="display:none;"
                        >
                            <label class="form-label">
                                Recipient
                            </label>

                            <select
                                name="numero_telephone_destinataire"
                                class="form-select"
                            >
                                <option value="">
                                    Select recipient
                                </option>

                                <?php foreach ($comptes as $compte): ?>
                                    <?php if ($compte['numero_telephone'] !== $data['numero_telephone']): ?>
                                        <option value="<?= esc($compte['numero_telephone']) ?>">
                                            <?= esc($compte['numero_telephone']) ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                            </select>
                        </div>

                        <button class="btn btn-primary w-100 py-2">
                            Submit Operation
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

<script src="<?= base_url('Assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<script>
const typeOperation = document.getElementById('type_operation');
const transferRecipientField = document.getElementById('transfer-recipient-field');
const transferRecipientSelect = transferRecipientField.querySelector('select');

function toggleTransferRecipientField() {
    const isTransfer = typeOperation.value === 'transfert';

    transferRecipientField.style.display = isTransfer ? 'block' : 'none';
    transferRecipientSelect.required = isTransfer;

    if (!isTransfer) {
        transferRecipientSelect.value = '';
    }
}

typeOperation.addEventListener('change', toggleTransferRecipientField);
toggleTransferRecipientField();
</script>

<script src="/Assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
