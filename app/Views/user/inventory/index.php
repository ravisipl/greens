<?= $this->extend('layouts/user') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">My Inventory</h3>
            <div class="float-right" style="float: right;">
                <button type="button" class="btn btn-primary" id="addInventoryBtn">
                    <i class="bi bi-plus-lg me-1"></i> Add Entry
                </button>
            </div>
        </div>
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