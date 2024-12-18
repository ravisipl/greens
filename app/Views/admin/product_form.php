<form id="productForm" action="<?= site_url('admin/products/save') ?>" method="post">
    <input type="hidden" name="id" value="<?= isset($product['id']) ? $product['id'] : '' ?>">
    
    <div class="mb-3">
        <label for="name" class="form-label">Product Name</label>
        <input type="text" class="form-control" name="name" id="name" 
               value="<?= isset($product) ? $product['name'] : '' ?>" required>
    </div>

   

    <div class="mb-3">
        <label for="size" class="form-label">Size</label>
        <select class="form-select" name="size" id="size" required>
            <option value="">Select Size</option>
            <option value="Small" <?= (isset($product) && $product['size'] == 'Small') ? 'selected' : '' ?>>Small</option>
            <option value="Medium" <?= (isset($product) && $product['size'] == 'Medium') ? 'selected' : '' ?>>Medium</option>
            <option value="Large" <?= (isset($product) && $product['size'] == 'Large') ? 'selected' : '' ?>>Large</option>
        </select>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Price</label>
        <div class="input-group">
            <span class="input-group-text">£</span>
            <input type="number" class="form-control" name="price" id="price" 
                   step="0.01" value="<?= isset($product) ? $product['price'] : '' ?>" required>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" onclick="$('#productDialog').dialog('close')">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
</form> 