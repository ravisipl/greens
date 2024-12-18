<form id="userForm" action="<?= $action_url ?>" method="post">
    <div id="validation-errors" class="alert alert-danger" style="display:none;"></div>
    
    <div class="form-group mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?? '' ?>" required>
        <div id="name-error" class="invalid-feedback"></div>
    </div>

    <div class="form-group mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="text" class="form-control" id="phone" name="phone" value="<?= $user['phone'] ?? '' ?>" required>
        <div id="phone-error" class="invalid-feedback"></div>
    </div>

    <div class="form-group mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" <?= isset($user) ? '' : 'required' ?>>
        <div id="password-error" class="invalid-feedback"></div>
    </div>

    <div class="form-group mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-select" id="role" name="role" required>
            <option value="">Select Role</option>
            <option value="admin" <?= (isset($user['role']) && $user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
            <option value="inventory_manager" <?= (isset($user['role']) && $user['role'] == 'inventory_manager') ? 'selected' : '' ?>>Inventory Manager</option>
            <option value="manufacturing_workers" <?= (isset($user['role']) && $user['role'] == 'manufacturing_workers') ? 'selected' : '' ?>>Manufacturing Workers</option>
        </select>
        <div id="role-error" class="invalid-feedback"></div>
    </div>

    <div class="dialog-footer">
        <button type="button" class="btn btn-secondary" onclick="$('#userDialog').dialog('close')">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
</form> 