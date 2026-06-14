<?php
require_once __DIR__ . '/../config/bootstrap.php';
$user = require_login($pdo);
require_role($user, ['student']);

$student = student_profile($pdo, (int)$user['id']);
if (!$student) redirect_to('students/edit.php');

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('
    SELECT c.*, u.full_name AS lecturer_name, e.enrolled_at
    FROM enrollments e
    JOIN courses c ON c.id = e.course_id
    LEFT JOIN users u ON u.id = c.lecturer_id
    WHERE e.student_id = ? AND c.id = ? AND c.is_active = TRUE
');
$stmt->execute([$student['id'], $id]);
$course = $stmt->fetch();

if (!$course) {
    redirect_to('courses/show.php?id=' . $id);
}

$fallbackContent = sprintf(
    "%s introduces the main ideas, vocabulary, and practical skills behind %s. In this course, focus first on understanding the purpose of the subject, then connect each topic to real examples and exercises. Read the notes carefully, pause after each section, and write down questions you want to ask your lecturer.\n\nKey things to learn:\n- What the course is about and why it matters.\n- The important concepts used in the field.\n- How to apply the ideas through examples and practice.\n- How the course connects to your wider program.\n\nStudy tip: watch the video once for the big picture, then watch again while taking notes. After that, summarize the lesson in your own words.",
    $course['code'],
    $course['title']
);

$learningContent = trim((string)($course['learning_content'] ?? '')) ?: $fallbackContent;
$pageTitle = $course['code'] . ' Learning';
require_once __DIR__ . '/../templates/header.php';
?>
<h1 class="page-title"><?php echo htmlspecialchars($course['title']); ?></h1>
<p class="page-subtitle"><?php echo htmlspecialchars($course['code']); ?> lesson room with <?php echo htmlspecialchars($course['lecturer_name'] ?? 'your lecturer'); ?></p>

<div class="two-column">
    <div class="card" style="padding:24px;">
        <div id="video-container" style="aspect-ratio:16/9; background:#0f172a; border-radius:12px; overflow:hidden; display:grid; place-items:center; color:white; margin-bottom:20px;">
            <?php if (!empty($course['video_url'])): ?>
                <iframe
                    id="video-iframe"
                    src="<?php echo htmlspecialchars($course['video_url']); ?>"
                    title="<?php echo htmlspecialchars($course['title']); ?> video lesson"
                    style="width:100%; height:100%; border:0;"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            <?php else: ?>
                <div style="text-align:center; padding:24px;">
                    <i class="fas fa-video" style="font-size:44px; margin-bottom:12px;"></i>
                    <h3 style="margin-bottom:8px;">Video lesson coming soon</h3>
                    <p style="color:#cbd5e1;">Your lecturer can add a YouTube embed URL from the course edit.</p>
                </div>
            <?php endif; ?>
        </div>

        <h3 id="reading-section" class="card-title" style="margin-bottom:12px;">Read and Understand</h3>
        <div style="color:var(--dark); line-height:1.8; white-space:pre-line;"><?php echo htmlspecialchars($learningContent); ?></div>
    </div>

    <div class="card" style="padding:24px;">
        <h3 class="card-title" style="margin-bottom:16px;">Course Guide</h3>
        <div class="activity-list" style="padding:0;">
            <div class="activity-item activity-clickable" data-action="scroll-to-reading" style="cursor:pointer; transition:var(--transition);">
                <div class="activity-avatar"><i class="fas fa-book-open"></i></div>
                <div class="activity-content">
                    <div class="activity-text"><strong>Start with the reading.</strong> Get familiar with the words and ideas before solving problems.</div>
                </div>
                <div class="activity-check" style="opacity:0; color:var(--success); font-size:18px;"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="activity-item activity-clickable" data-action="play-video" style="cursor:pointer; transition:var(--transition);">
                <div class="activity-avatar"><i class="fas fa-play"></i></div>
                <div class="activity-content">
                    <div class="activity-text"><strong>Watch the video.</strong> Pause and replay difficult parts until the explanation is clear.</div>
                </div>
                <div class="activity-check" style="opacity:0; color:var(--success); font-size:18px;"><i class="fas fa-check-circle"></i></div>
            </div>
            <div class="activity-item activity-clickable" data-action="open-summary-modal" style="cursor:pointer; transition:var(--transition);">
                <div class="activity-avatar"><i class="fas fa-pen"></i></div>
                <div class="activity-content">
                    <div class="activity-text"><strong>Write a summary.</strong> Explain the course topic in your own words after studying.</div>
                </div>
                <div class="activity-check" style="opacity:0; color:var(--success); font-size:18px;"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div style="margin-top:20px; color:var(--gray); line-height:1.7;">
            <strong>Enrolled:</strong> <?php echo date('M j, Y', strtotime($course['enrolled_at'])); ?><br>
            <strong>Credit Units:</strong> <?php echo (int)$course['credit_units']; ?><br>
            <strong>Payment:</strong> <?php echo !empty($course['is_paid']) ? 'Paid - NGN ' . number_format((float)$course['price'], 2) : 'Free'; ?>
        </div>
        <button class="btn btn-secondary" style="margin-top:20px;" onclick="window.location='<?php echo app_url('dashboard/student.php'); ?>'">Back to Dashboard</button>
    </div>
</div>

<!-- Summary Modal -->
<div class="modal-overlay" id="summaryModal">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Write Your Summary</h3>
            <button class="modal-close" onclick="closeSummaryModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Course Notes / Summary</label>
                <textarea id="summary-textarea" class="form-input form-textarea" placeholder="Explain the course topic in your own words..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeSummaryModal()">Cancel</button>
            <button class="btn btn-primary" onclick="saveSummary()">Save Summary</button>
        </div>
    </div>
</div>

<script>
// Course Guide Interactivity
document.addEventListener('DOMContentLoaded', function() {
    const activityItems = document.querySelectorAll('.activity-clickable');
    
    activityItems.forEach(item => {
        item.addEventListener('click', function() {
            const action = this.dataset.action;
            
            switch(action) {
                case 'scroll-to-reading':
                    scrollToReading(this);
                    break;
                case 'play-video':
                    playVideo(this);
                    break;
                case 'open-summary-modal':
                    openSummaryModal(this);
                    break;
            }
        });
        
        // Add hover effect
        item.addEventListener('mouseenter', function() {
            this.style.background = 'var(--light-gray)';
        });
        
        item.addEventListener('mouseleave', function() {
            if (!this.classList.contains('completed')) {
                this.style.background = '';
            }
        });
    });
    
    // Load saved summary if exists
    loadSavedSummary();
});

function scrollToReading(element) {
    const readingSection = document.getElementById('reading-section');
    if (readingSection) {
        readingSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        markAsCompleted(element);
    }
}

function playVideo(element) {
    const videoContainer = document.getElementById('video-container');
    const iframe = document.getElementById('video-iframe');
    
    if (videoContainer && iframe) {
        videoContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        // Try to play the video by adding autoplay parameter
        const currentSrc = iframe.src;
        if (currentSrc && !currentSrc.includes('autoplay=1')) {
            iframe.src = currentSrc + (currentSrc.includes('?') ? '&' : '?') + 'autoplay=1';
        }
        
        markAsCompleted(element);
    } else {
        showToast('Video not available for this course');
    }
}

function openSummaryModal(element) {
    const modal = document.getElementById('summaryModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Store the element to mark as completed after saving
        modal.dataset.activityElement = element ? element.closest('.activity-clickable').outerHTML : '';
    }
}

function closeSummaryModal() {
    const modal = document.getElementById('summaryModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

async function saveSummary() {
    const textarea = document.getElementById('summary-textarea');
    const summary = textarea.value.trim();
    const courseId = <?php echo $id; ?>;
    
    if (!summary) {
        showToast('Please write a summary before saving');
        return;
    }
    
    try {
        const response = await fetch('save_summary.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                course_id: courseId,
                summary: summary
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Summary saved successfully!');
            closeSummaryModal();
            
            // Mark the activity as completed
            const activityItems = document.querySelectorAll('.activity-clickable');
            activityItems.forEach(item => {
                if (item.dataset.action === 'open-summary-modal') {
                    markAsCompleted(item);
                }
            });
        } else {
            showToast('Error: ' + (result.message || 'Failed to save summary'));
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Network error. Please try again.');
    }
}

async function loadSavedSummary() {
    const courseId = <?php echo $id; ?>;
    
    try {
        const response = await fetch(`get_summary.php?course_id=${courseId}`);
        const result = await response.json();
        
        if (result.success && result.summary) {
            const textarea = document.getElementById('summary-textarea');
            if (textarea) {
                textarea.value = result.summary;
            }
            
            // Mark the activity as completed if summary exists
            const activityItems = document.querySelectorAll('.activity-clickable');
            activityItems.forEach(item => {
                if (item.dataset.action === 'open-summary-modal') {
                    markAsCompleted(item);
                }
            });
        }
    } catch (error) {
        console.error('Error loading summary:', error);
    }
}

function markAsCompleted(element) {
    element.classList.add('completed');
    element.style.background = '#d1fae5';
    element.style.opacity = '0.8';
    
    const checkIcon = element.querySelector('.activity-check');
    if (checkIcon) {
        checkIcon.style.opacity = '1';
    }
    
    // Save completion state to localStorage
    const courseId = <?php echo $id; ?>;
    const action = element.dataset.action;
    localStorage.setItem(`course_${courseId}_${action}`, 'completed');
    
    // Load saved completion states
    loadCompletionStates();
}

function loadCompletionStates() {
    const courseId = <?php echo $id; ?>;
    const actions = ['scroll-to-reading', 'play-video', 'open-summary-modal'];
    
    actions.forEach(action => {
        const isCompleted = localStorage.getItem(`course_${courseId}_${action}`);
        if (isCompleted === 'completed') {
            const element = document.querySelector(`.activity-clickable[data-action="${action}"]`);
            if (element && !element.classList.contains('completed')) {
                markAsCompleted(element);
            }
        }
    });
}

// Close modal on outside click
document.getElementById('summaryModal').addEventListener('click', function(e) {
    if (e.target === this) closeSummaryModal();
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeSummaryModal();
});
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
