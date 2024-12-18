<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-4">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Products Management</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </nav>
            </div>
            <button type="button" class="btn btn-primary add-product-btn">
                <i class="bi bi-plus-lg me-1"></i> Add Product
            </button>
        </div>
    </div>

    <!-- Products List -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="productsTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Product Form Dialog -->
<div id="productDialog" title="Product" style="display:none;">
    <div id="productFormContent"></div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }

    jQuery(function($) {
        // Initialize DataTable
        var table = $('#productsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= site_url('admin/products/getProducts') ?>',
                type: 'POST',
                error: function(xhr, error, thrown) {
                    alert('Error loading product data. Please try again.');
                }
            },
            columns: [
                { data: 'name' },
                { 
                    data: 'size',
                    render: function(data, type, row) {
                        return '<span class="badge bg-success rounded-pill">' + data + '</span>';
                    }
                },
                { 
                    data: 'price',
                    render: function(data, type, row) {
                        return '£' + parseFloat(data).toFixed(2);
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-sm btn-outline-primary edit-product-btn" data-id="${row.id}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-product-btn" data-id="${row.id}">
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

        // Initialize dialog with improved config
        $("#productDialog").dialog({
            autoOpen: false,
            modal: true,
            width: 500,
            height: 'auto',
            closeOnEscape: true,
            draggable: false,
            resizable: false,
            buttons: {
                Close: function() {
                    $(this).dialog('close');
                }
            }
        });

        // AJAX handlers with improved error handling
        $('.add-product-btn').click(function(e) {
            e.preventDefault();
            $.get('<?= site_url('admin/products/add') ?>')
                .done(function(data) {
                    $('#productFormContent').html(data);
                    $('#validation-errors').hide();
                    $('#productDialog').dialog('open');
                })
                .fail(function() {
                    alert('Error loading product form. Please try again.');
                });
        });

        $('#productsTable').on('click', '.edit-product-btn', function(e) {
            e.preventDefault();
            var productId = $(this).data('id');
            $.get('<?= site_url('admin/products/edit/') ?>' + productId)
                .done(function(data) {
                    $('#productFormContent').html(data);
                    $('#validation-errors').hide();
                    $('#productDialog').dialog('open');
                })
                .fail(function() {
                    alert('Error loading product data. Please try again.');
                });
        });

        // Improved form submission with validation handling
        $(document).on('submit', '#productForm', function(e) {
            e.preventDefault();
            var $form = $(this);
            var $submitButton = $form.find('button[type="submit"]');
            
            // Clear previous validation states
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').html('');
            $('#validation-errors').hide();
            
            // Disable submit button to prevent double submission
            $submitButton.prop('disabled', true);

            $.ajax({
                url: $form.attr('action'),
                method: 'POST',
                data: $form.serialize(),
                success: function(response) {
                    if(response.success) {
                        $('#productDialog').dialog('close');
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
                            alert(response.message || 'Error saving product');
                        }
                    }
                },
                error: function() {
                    alert('Error saving product. Please try again.');
                },
                complete: function() {
                    $submitButton.prop('disabled', false);
                }
            });
        });

        // Delete handler remains mostly the same but with improved error handling
        $('#productsTable').on('click', '.delete-product-btn', function(e) {
            e.preventDefault();
            var productId = $(this).data('id');
            if (confirm('Are you sure you want to delete this product?')) {
                $.post('<?= site_url('admin/products/delete/') ?>' + productId)
                    .done(function(response) {
                        if (response.success) {
                            table.ajax.reload();
                        } else {
                            alert(response.message || 'Error deleting product');
                        }
                    })
                    .fail(function(xhr) {
                        alert(xhr.responseJSON?.message || 'Error deleting product. Please try again.');
                    });
            }
        });
    });
});
</script>
<?= $this->endSection() ?>

