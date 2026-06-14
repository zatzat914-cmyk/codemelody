<?php $lecturer = $lecturer ?? []; ?>
<div class="form-group">
    <label class="form-label">Full Name</label>
    <input class="form-input" type="text" name="full_name" value="<?php echo htmlspecialchars($lecturer['full_name'] ?? ''); ?>" required>
</div>
<div class="form-group">
    <label class="form-label">Email</label>
    <input class="form-input" type="email" name="email" value="<?php echo htmlspecialchars($lecturer['email'] ?? ''); ?>" required>
</div>
