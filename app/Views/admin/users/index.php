<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Users Management</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </nav>
            </div>
            <button type="button" class="btn btn-primary add-user-btn">
                <i class="bi bi-plus-lg me-1"></i> Add User
            </button>
        </div>
    </div>

    <!-- Users List -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="usersTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- User Form Dialog -->
<div id="userDialog" title="User Management" style="display:none;">
    <div id="validation-errors" class="alert alert-danger" style="display:none;"></div>
    <div id="userFormContent"></div>
</div>

<!-- Add DataTables CSS and JS -->
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<style>
/* Dialog styling */
.ui-dialog {
    padding: 0;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.ui-dialog .ui-dialog-titlebar {
    background: #f8f9fa;
    border: none;
    border-bottom: 1px solid #dee2e6;
    padding: 15px;
}

.ui-dialog .ui-dialog-content {
    padding: 20px;
}

.ui-dialog-titlebar-close {
    border: none !important;
    background: transparent !important;
    color: #6c757d;
}

/* Form styling */
.form-group {
    margin-bottom: 1rem;
}

.dialog-footer {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #dee2e6;
    text-align: right;
}

.dialog-footer .btn {
    margin-left: 10px;
}
</style>
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= site_url('admin/users/getUsers') ?>',
            type: 'POST'
        },
        columns: [
            { data: 'name' },
            { data: 'phone' },
            { 
                data: 'role',
                render: function(data, type, row) {
                    let badgeClass = '';
                    switch(data.toLowerCase()) {
                        case 'admin':
                            badgeClass = 'bg-primary';
                            break;
                        case 'inventory manager':
                            badgeClass = 'bg-success';
                            break;
                        case 'manufacturing workers':
                            badgeClass = 'bg-info';
                            break;
                        default:
                            badgeClass = 'bg-secondary';
                    }
                    return '<span class="badge ' + badgeClass + ' rounded-pill">' + data + '</span>';
                }
            },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary edit-user-btn" data-id="${row.id}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-user-btn" data-id="${row.id}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        responsive: true,
        order: [[0, 'asc']]
    });

    // Handle Add User button click
    $('.add-user-btn').click(function(e) {
        e.preventDefault();
        $.get('<?= site_url('admin/users/add') ?>', function(data) {
            $('#userFormContent').html(data);
            $('#userDialog').dialog('open');
        });
    });

    // Handle Edit button click (delegated event)
    $('#usersTable').on('click', '.edit-user-btn', function(e) {
        e.preventDefault();
        var userId = $(this).data('id');
        $.get('<?= site_url('admin/users/edit/') ?>' + userId, function(data) {
            $('#userFormContent').html(data);
            $('#userDialog').dialog('open');
        });
    });

    // Handle Delete button click (delegated event)
    $('#usersTable').on('click', '.delete-user-btn', function(e) {
        e.preventDefault();
        var userId = $(this).data('id');
        if (confirm('Are you sure you want to delete this user?')) {
            $.post('<?= site_url('admin/users/delete/') ?>' + userId, function(response) {
                if (response.success) {
                    table.ajax.reload();
                } else {
                    alert('Error deleting user');
                }
            });
        }
    });

    // Initialize dialog
    $("#userDialog").dialog({
        autoOpen: false,
        modal: true,
        width: 500,
        height: 'auto',
        resizable: false,
        close: function() {
            $('#userFormContent').html('');
            $('#validation-errors').hide();
        }
    });

    // Handle form submission
    $(document).on('submit', '#userForm', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    $('#userDialog').dialog('close');
                    table.ajax.reload();
                } else {
                    if(response.errors) {
                        // Display validation errors
                        let errorHtml = '<ul class="mb-0">';
                        $.each(response.errors, function(field, message) {
                            $('#' + field).addClass('is-invalid');
                            $('#' + field + '-error').html(message);
                            errorHtml += '<li>' + message + '</li>';
                        });
                        errorHtml += '</ul>';
                        $('#validation-errors').html(errorHtml).show();
                    } else {
                        alert(response.message || 'Error saving user');
                    }
                }
            }
        });
    });
});
</script>
<?= $this->endSection() ?> 