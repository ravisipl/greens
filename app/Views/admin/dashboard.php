<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <h3>Total Products</h3>
                <div class="value"><?= $total_products ?></div>
                <div class="icon">
                    <i class="bi bi-box"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card success">
                <h3>Total Users</h3>
                <div class="value"><?= $total_users ?></div>
                <div class="icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card warning">
                <h3>Today's Total</h3>
                <div class="value">₹<?= number_format($today_total, 2) ?></div>
                <div class="icon">
                    <i class="bi bi-currency-rupee"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card info">
                <h3>Month's Total</h3>
                <div class="value">₹<?= number_format($month_total, 2) ?></div>
                <div class="icon">
                    <i class="bi bi-currency-rupee"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Statistics -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Today's Employee Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Entries</th>
                                    <th>Quantity</th>
                                    <th>Total Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($today_stats)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No entries for today</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($today_stats as $stat): ?>
                                        <tr>
                                            <td><?= esc($stat['employee_name']) ?></td>
                                            <td><?= $stat['total_entries'] ?></td>
                                            <td><?= $stat['total_quantity'] ?></td>
                                            <td>₹<?= number_format($stat['total_cost'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">This Month's Employee Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Entries</th>
                                    <th>Quantity</th>
                                    <th>Total Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(empty($month_stats)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No entries for this month</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach($month_stats as $stat): ?>
                                        <tr>
                                            <td><?= esc($stat['employee_name']) ?></td>
                                            <td><?= $stat['total_entries'] ?></td>
                                            <td><?= $stat['total_quantity'] ?></td>
                                            <td>₹<?= number_format($stat['total_cost'], 2) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stats-card {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.05);
    position: relative;
    overflow: hidden;
}

.stats-card h3 {
    font-size: 1rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.stats-card .value {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2c3e50;
}

.stats-card .icon {
    position: absolute;
    right: 1.5rem;
    top: 50%;
    transform: translateY(-50%);
    font-size: 2rem;
    opacity: 0.2;
}

.stats-card.success .value { color: #2ecc71; }
.stats-card.warning .value { color: #f1c40f; }
.stats-card.info .value { color: #3498db; }

.table th {
    background: #f8f9fa;
    font-weight: 600;
}
</style>
<?= $this->endSection() ?> 