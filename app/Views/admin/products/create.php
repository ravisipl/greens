<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Add New Product</h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('admin/products/store') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" class="form-control <?= session('errors.name') ? 'is-invalid' : '' ?>" 
                           id="name" name="name" value="<?= old('name') ?>">
                    <?php if (session('errors.name')): ?>
                        <div class="invalid-feedback">
                            <?= session('errors.name') ?>
                        </div>
                    <?php endif; ?>
                </div>

                    <div class="mb-3">
                    <label for="size" class="form-label">Size</label>
                    <select class="form-select <?= session('errors.size') ? 'is-invalid' : '' ?>" 
                            id="size" name="size">
                        <option value="">Select Size</option>
                        <option value="Small" <?= old('size') == 'Small' ? 'selected' : '' ?>>Small</option>
                        <option value="Medium" <?= old('size') == 'Medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="Large" <?= old('size') == 'Large' ? 'selected' : '' ?>>Large</option>
                    </select>
                    <?php if (session('errors.size')): ?>
                        <div class="invalid-feedback">
                            <?= session('errors.size') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" class="form-control <?= session('errors.price') ? 'is-invalid' : '' ?>" 
                               id="price" name="price" value="<?= old('price') ?>">
                        <?php if (session('errors.price')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.price') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('admin/products') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 