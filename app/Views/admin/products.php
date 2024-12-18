<div id="productDialog" title="Product" style="display:none;">
    <div id="productFormContent"></div>
</div>

<script>
$(document).ready(function() {
    // Initialize dialog
    $("#productDialog").dialog({
        autoOpen: false,
        modal: true,
        width: 500,
        height: 'auto'
    });

    // Handle Add Product button click
    $('.add-product-btn').click(function(e) {
        e.preventDefault();
        $.get('<?= site_url('admin/products/add') ?>', function(data) {
            $('#productFormContent').html(data);
            $('#productDialog').dialog('open');
        });
    });

    // Handle Edit button click
    $('.edit-product-btn').click(function(e) {
        e.preventDefault();
        var productId = $(this).data('id');
        $.get('<?= site_url('admin/products/edit/') ?>' + productId, function(data) {
            $('#productFormContent').html(data);
            $('#productDialog').dialog('open');
        });
    });

    // Handle form submission
    $(document).on('submit', '#productForm', function(e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if(response.success) {
                    $('#productDialog').dialog('close');
                    location.reload(); // Reload to show updated data
                } else {
                    alert('Error saving product');
                }
            }
        });
    });
});
</script>

<!-- Update your Add Product button -->
<button class="add-product-btn">Add Product</button>

<!-- Update your product listing table -->
<table>
    <!-- Existing table headers... -->
    <tr>
        <td><?= $product['name'] ?></td>
        <td><?= $product['size'] ?></td>
        <td><?= $product['price'] ?></td>
        <td>
            <button class="edit-product-btn" data-id="<?= $product['id'] ?>">Edit</button>
            <!-- Other action buttons... -->
        </td>
    </tr>
    <!-- ... -->
</table> 