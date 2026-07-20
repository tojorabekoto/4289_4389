<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>

    <link rel="stylesheet" href="<?= base_url('Assets/vendor/bootstrap/css/bootstrap.min.css') ?>">
</head>
<body class="bg-light">

<div class="container vh-100 d-flex align-items-center justify-content-center">

    <div class="card shadow-lg border-0 rounded-4" style="max-width: 450px; width:100%;">
        <div class="card-body p-5">

            <h2 class="text-center fw-bold text-primary mb-4">
                Create an Account
            </h2>

            <p class="text-center text-muted mb-4">
                Enter your phone number to create your account.
            </p>

            <form method="POST" action="/client/add">

                <div class="mb-4">
                    <label class="form-label">Phone Number</label>

                    <input
                        type="text"
                        class="form-control form-control-lg"
                        name="numero_telephone"
                        placeholder="e.g. 0341234567"
                        required
                    >
                </div>

                <button class="btn btn-primary btn-lg w-100">
                    Create Account
                </button>

            </form>

        </div>
    </div>

</div>

<script src="<?= base_url('Assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

</body>
</html>
