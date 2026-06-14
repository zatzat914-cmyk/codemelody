<?php $student = $student ?? []; ?>
<div class="form-group">
    <label class="form-label">Full Name</label>
    <input class="form-input" type="text" name="full_name" value="<?php echo htmlspecialchars($student['full_name'] ?? ''); ?>" required>
</div>
<div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
    <div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" name="email" value="<?php echo htmlspecialchars($student['email'] ?? ''); ?>" required></div>
    <div class="form-group"><label class="form-label">Matric Number</label><input class="form-input" type="text" name="matric_no" value="<?php echo htmlspecialchars($student['matric_no'] ?? ''); ?>" required></div>
</div>
<div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
    <div class="form-group"><label class="form-label">Program</label><input class="form-input" type="text" name="program" value="<?php echo htmlspecialchars($student['program'] ?? 'Computer Science'); ?>"></div>
    <div class="form-group"><label class="form-label">Level</label><input class="form-input" type="number" name="level" min="100" step="100" value="<?php echo htmlspecialchars($student['level'] ?? 200); ?>"></div>
</div>
