<nav class="admin-navbar">
    <div class="navbar-content">
        <div class="d-flex align-items-center">
            <button 
                class="btn btn-link d-md-none me-3 sidebar-toggle" 
                id="sidebar-toggle" 
                aria-label="Toggle Sidebar" 
                aria-expanded="false" 
                aria-controls="admin-sidebar"
                type="button">
                <i class="bi bi-list fs-4"></i>
            </button>
            <h4 class="mb-0"><?= $title ?? 'Dashboard' ?></h4>
        </div>
        <div class="d-flex align-items-center">
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i>
                    <span class="ms-2"><?= session()->get('user_name') ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="<?= base_url('admin/profile') ?>">
                        <i class="bi bi-person me-2"></i> Profile
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= base_url('auth/logout') ?>">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a></li>
                </ul>
            </div>
        </div>
    </div>
</nav> 