<?php
$userRole = session()->get('role');
?>

<div class="sidebar-nav">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?= url_is('admin/dashboard*') ? 'active' : '' ?>" 
               href="<?= base_url('admin/dashboard') ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= url_is('admin/products*') ? 'active' : '' ?>" 
               href="<?= base_url('admin/products') ?>">
                <i class="bi bi-box"></i>
                <span>Products</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= url_is('admin/users*') ? 'active' : '' ?>" 
               href="<?= base_url('admin/users') ?>">
                <i class="bi bi-people"></i>
                <span>Users</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= url_is('admin/inventory*') ? 'active' : '' ?>" 
               href="<?= base_url('admin/inventory') ?>">
                <i class="bi bi-box-seam"></i>
                <span>Inventory</span>
            </a>
        </li>
    </ul>
</div> 