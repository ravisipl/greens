<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }

    jQuery(function($) {
        let productPrices = {};
        let deleteId = null;
        
        // Common DataTable initialization
        var table = $('#inventoryTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url($viewPrefix . "/inventory/getInventory") ?>',
                type: 'POST'
            },
            columns: [
                { data: 'product_name' },
                { data: 'product_size' },
                { data: 'employee_name' },
                { data: 'issued_quantity' },
                { data: 'received_quantity' },
                { 
                    data: 'cost',
                    render: function(data) {
                        return '₹' + new Intl.NumberFormat('en-IN', {
                            maximumFractionDigits: 2,
                            minimumFractionDigits: 2
                        }).format(data);
                    }
                },
                { 
                    data: 'created_at',
                    render: function(data) {
                        return new Date(data).toLocaleString();
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data) {
                        return `
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-sm btn-primary edit-inventory" data-id="${data.id}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-danger delete-inventory" data-id="${data.id}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ]
        });

        // Product size change handler
        $('#product_name').change(function() {
            let productName = $(this).val();
            if (productName) {
                $.ajax({
                    url: '<?= base_url($viewPrefix . "/inventory/getSizes") ?>',
                    type: 'POST',
                    data: { product_name: productName },
                    success: function(response) {
                        if (response.success) {
                            let options = '<option value="">Select Size</option>';
                            response.sizes.forEach(function(size) {
                                options += `<option value="${size.id}" data-price="${size.price}">${size.size}</option>`;
                                productPrices[size.id] = size.price;
                            });
                            $('#product_size').html(options);
                        }
                    }
                });
            } else {
                $('#product_size').html('<option value="">Select Size</option>');
            }
        });

        // Size change handler to update cost
        $('#product_size').change(function() {
            let sizeId = $(this).val();
            let price = sizeId ? productPrices[sizeId] : 0;
            let quantity = $('#issued_quantity').val() || 0;
            updateTotalCost(price, quantity);
        });

        // Quantity change handler
        $('#issued_quantity').on('input', function() {
            let sizeId = $('#product_size').val();
            let price = sizeId ? productPrices[sizeId] : 0;
            let quantity = $(this).val() || 0;
            updateTotalCost(price, quantity);
        });

        function updateTotalCost(price, quantity) {
            let totalCost = price * quantity;
            $('#total_cost').val(totalCost.toFixed(2));
        }

        // Add inventory button click handler
        $('#addInventoryBtn').click(function() {
            $('#inventory_id').val('');
            $('#inventoryForm')[0].reset();
            $('#inventoryDialog').modal('show');
        });

        // Edit inventory handler
        $(document).on('click', '.edit-inventory', function() {
            let id = $(this).data('id');
            $.ajax({
                url: '<?= base_url($viewPrefix . "/inventory/edit") ?>/' + id,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        let data = response.data;
                        $('#inventory_id').val(data.id);
                        $('#product_name').val(data.product_name).trigger('change');
                        
                        // Wait for sizes to load before setting the product size
                        setTimeout(function() {
                            $('#product_size').val(data.product_id);
                        }, 500);
                        
                        $('#employee_id').val(data.employee_id);
                        $('#issued_quantity').val(data.issued_quantity);
                        $('#received_quantity').val(data.received_quantity);
                        $('#total_cost').val(data.cost);
                        $('#inventoryDialog').modal('show');
                    }
                }
            });
        });

        // Save inventory handler
        $('#saveInventory').click(function() {
            let formData = $('#inventoryForm').serialize();
            let url = $('#inventory_id').val() ? 
                '<?= base_url($viewPrefix . "/inventory/update") ?>' : 
                '<?= base_url($viewPrefix . "/inventory/save") ?>';

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#inventoryDialog').modal('hide');
                        table.ajax.reload();
                    } else {
                        alert(response.message || 'Error saving inventory');
                    }
                }
            });
        });

        // Delete inventory button click handler
        $(document).on('click', '.delete-inventory', function() {
            deleteId = $(this).data('id');
            $('#deleteDialog').modal('show');
        });

        // Confirm delete handler
        $('#confirmDelete').click(function() {
            if (deleteId) {
                $.ajax({
                    url: '<?= base_url($viewPrefix . "/inventory/delete") ?>/' + deleteId,
                    type: 'POST',
                    success: function(response) {
                        if (response.success) {
                            $('#deleteDialog').modal('hide');
                            table.ajax.reload();
                        } else {
                            alert(response.message || 'Error deleting inventory');
                        }
                    }
                });
            }
        });
    });
});
</script> 