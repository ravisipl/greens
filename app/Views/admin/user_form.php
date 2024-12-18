<form id="userForm" action="<?= site_url('admin/users/save') ?>" method="post">
    <input type="hidden" name="id" value="<?= isset($user['id']) ? $user['id'] : '' ?>">
    
    <div id="validation-errors" class="alert alert-danger" style="display: none;"></div>
    
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" name="name" id="name" 
               value="<?= isset($user) ? $user['name'] : '' ?>" required>
        <div class="invalid-feedback" id="name-error"></div>
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input type="tel" class="form-control" name="phone" id="phone" 
               value="<?= isset($user) ? $user['phone'] : '' ?>" required>
        <div class="invalid-feedback" id="phone-error"></div>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password <?= isset($user) ? '(Leave blank to keep current)' : '' ?></label>
        <input type="password" class="form-control" name="password" id="password" 
               <?= isset($user) ? '' : 'required' ?>>
        <div class="invalid-feedback" id="password-error"></div>
    </div>

    <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-select" name="role" id="role" required>
            <option value="">Select Role</option>
            <option value="Manufacturing Workers" <?= (isset($user) && strtolower($user['role']) == 'manufacturing workers') ? 'selected' : '' ?>>Manufacturing Workers</option>
            <option value="Inventory Manager" <?= (isset($user) && strtolower($user['role']) == 'inventory manager') ? 'selected' : '' ?>>Inventory Manager</option>
            <option value="Admin" <?= (isset($user) && strtolower($user['role']) == 'admin') ? 'selected' : '' ?>>Admin</option>
        </select>
        <div class="invalid-feedback" id="role-error"></div>
    </div>

    <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" onclick="$('#userDialog').dialog('close')">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </div>
</form> 