<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-4">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Inventory Management</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Inventory</li>
                    </ol>
                </nav>
            </div>
            <button type="button" class="btn btn-primary" id="addInventoryBtn">
                <i class="bi bi-plus-lg me-1"></i> Add Entry
            </button>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="inventoryTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Size</th>
                            <th>Employee</th>
                            <th>Issued Qty</th>
                            <th>Received Qty</th>
                            <th>Cost</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->include('inventory/dialog_template') ?>

<?= $this->section('scripts') ?>
<!-- Add any additional scripts needed for this page -->
<?= $this->include('inventory/common_scripts') ?>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 