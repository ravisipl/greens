<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card mt-5">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="logo-wrapper mb-3">
                            <img src="<?= base_url('assets/admin/images/logo.png') ?>" alt="Anandj Greens" class="logo">
                        </div>
                        <h4 class="card-title" style="font-size: 20px; color: #333;">Login</h4>
                    </div>

                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger">
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('auth/login') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" style="background-color: #4CAF50; border-color: #4CAF50;">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border-radius: 10px;
}

.card-body {
    padding: 2rem;
}

.logo-wrapper {
    margin-bottom: 1.5rem;
}

.logo {
    max-width: 200px;
    height: auto;
}

.form-control {
    padding: 0.75rem 1rem;
    border-radius: 5px;
}

.btn-primary:hover {
    background-color: #45a049 !important;
    border-color: #45a049 !important;
}
</style>
<?= $this->endSection() ?> 