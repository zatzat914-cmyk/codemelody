        </div>
    </main>

    <!-- Shared Create Course Modal -->
    <div class="modal-overlay" id="createModal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title">Create New Course</h2>
                <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <form id="courseForm">
                    <div class="form-group">
                        <label class="form-label">Course Code</label>
                        <input type="text" name="code" class="form-input" placeholder="e.g., HUI-CSC 202" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Course Title</label>
                        <input type="text" name="title" class="form-input" placeholder="e.g., Big Data Computing" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Credit Units</label>
                        <select name="credit_units" class="form-input form-select">
                            <option value="1">1 Unit</option>
                            <option value="2" selected>2 Units</option>
                            <option value="3">3 Units</option>
                            <option value="4">4 Units</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-input form-select">
                            <option value="compulsory">Compulsory (C)</option>
                            <option value="elective">Elective (E)</option>
                            <option value="required">Required (R)</option>
                        </select>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="form-group">
                            <label class="form-label">Lecture Hours (LH)</label>
                            <input type="number" name="lecture_hours" class="form-input" value="30">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Practical Hours (PH)</label>
                            <input type="number" name="practical_hours" class="form-input" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-input form-textarea" placeholder="Course description..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button class="btn btn-primary" onclick="createCourse()">Create Course</button>
            </div>
        </div>
    </div>

    <!-- Toast Notifications Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script src="assets/js/main.js"></script>
    <script src="assets/js/dashboard.js"></script>
    <?php if (isset($extraJs)): ?>
        <?php foreach ($extraJs as $js): ?>
            <script src="<?php echo htmlspecialchars($js); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
