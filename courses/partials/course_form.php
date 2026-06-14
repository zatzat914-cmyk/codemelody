<?php $course = $course ?? []; ?>
<div class="form-group">
    <label class="form-label">Course Code</label>
    <input class="form-input" type="text" name="code" value="<?php echo htmlspecialchars($course['code'] ?? ''); ?>" required>
</div>
<div class="form-group">
    <label class="form-label">Course Title</label>
    <input class="form-input" type="text" name="title" value="<?php echo htmlspecialchars($course['title'] ?? ''); ?>" required>
</div>
<div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:16px;">
    <div class="form-group"><label class="form-label">Credit Units</label><input class="form-input" type="number" name="credit_units" min="1" value="<?php echo htmlspecialchars($course['credit_units'] ?? 2); ?>"></div>
    <div class="form-group"><label class="form-label">Lecture Hours</label><input class="form-input" type="number" name="lecture_hours" min="0" value="<?php echo htmlspecialchars($course['lecture_hours'] ?? 30); ?>"></div>
    <div class="form-group"><label class="form-label">Practical Hours</label><input class="form-input" type="number" name="practical_hours" min="0" value="<?php echo htmlspecialchars($course['practical_hours'] ?? 0); ?>"></div>
</div>
<div class="form-group">
    <label class="form-label">Status</label>
    <select class="form-input form-select" name="status">
        <?php foreach (['compulsory', 'elective', 'required'] as $status): ?>
            <option value="<?php echo $status; ?>" <?php echo (($course['status'] ?? 'compulsory') === $status) ? 'selected' : ''; ?>><?php echo ucfirst($status); ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
    <div class="form-group">
        <label class="form-label">Payment Type</label>
        <select class="form-input form-select" name="is_paid">
            <option value="0" <?php echo empty($course['is_paid']) ? 'selected' : ''; ?>>Free</option>
            <option value="1" <?php echo !empty($course['is_paid']) ? 'selected' : ''; ?>>Paid</option>
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Price</label>
        <input class="form-input" type="number" name="price" min="0" step="0.01" value="<?php echo htmlspecialchars($course['price'] ?? '0.00'); ?>">
    </div>
</div>
<div class="form-group">
    <label class="form-label">Description</label>
    <textarea class="form-input form-textarea" name="description"><?php echo htmlspecialchars($course['description'] ?? ''); ?></textarea>
</div>
<div class="form-group">
    <label class="form-label">Lesson Video URL</label>
    <input class="form-input" type="url" name="video_url" placeholder="https://www.youtube.com/embed/..." value="<?php echo htmlspecialchars($course['video_url'] ?? ''); ?>">
</div>
<div class="form-group">
    <label class="form-label">Learning Content</label>
    <textarea class="form-input form-textarea" name="learning_content" placeholder="Write the student-facing lesson notes, explanations, examples, and reading material."><?php echo htmlspecialchars($course['learning_content'] ?? ''); ?></textarea>
</div>
