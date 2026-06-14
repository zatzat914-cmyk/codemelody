function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('active');
    if (overlay) overlay.classList.toggle('active');
}
function openModal() {
    document.getElementById('createModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('createModal').classList.remove('active');
    document.body.style.overflow = '';
}

async function createCourse() {
    const form = document.getElementById('courseForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    try {
        const response = await fetch('courses.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            closeModal();
            showToast('Course created successfully! 🎉');
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('Error: ' + (result.message || 'Failed to create course'));
        }
    } catch (error) {
        console.error('Error:', error);
        showToast('Network error');
    }
}

function showToast(message) {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `
        <div class="toast-icon"><i class="fas fa-check"></i></div>
        <div class="toast-content"><h4>Success</h4><p>${message}</p></div>
    `;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

let notificationsLoaded = false;

async function loadNotifications() {
    const list = document.getElementById('notificationList');
    const badge = document.getElementById('notificationBadge');
    const dot = document.getElementById('notificationDot');
    if (!list) return;

    list.innerHTML = `
        <div class="notif-skeleton">
            <div class="notif-skeleton-row"><div class="notif-skeleton-circle"></div><div class="notif-skeleton-lines"><div class="notif-skeleton-line w-75"></div><div class="notif-skeleton-line w-50"></div></div></div>
            <div class="notif-skeleton-row"><div class="notif-skeleton-circle"></div><div class="notif-skeleton-lines"><div class="notif-skeleton-line w-75"></div><div class="notif-skeleton-line w-50"></div></div></div>
            <div class="notif-skeleton-row"><div class="notif-skeleton-circle"></div><div class="notif-skeleton-lines"><div class="notif-skeleton-line w-75"></div><div class="notif-skeleton-line w-50"></div></div></div>
        </div>
    `;

    try {
        const response = await fetch(APP_BASE + '/api/get_notifications.php');
        const data = await response.json();

        if (!data.success) {
            list.innerHTML = '<div class="notification-empty">Failed to load notifications.</div>';
            return;
        }

        if (data.notifications.length === 0) {
            list.innerHTML = `
                <div class="notification-empty">
                    <div class="notif-empty-icon"><i class="fas fa-bell"></i></div>
                    <span>You're all caught up!</span>
                </div>`;
        } else {
            list.innerHTML = data.notifications.map(n => `
                <div class="notification-item ${n.is_read ? '' : 'unread'}" data-id="${n.id}" onclick="markOneRead(${n.id})">
                    <div class="notif-icon notif-${n.type}"><i class="fas ${notifIcon(n.type)}"></i></div>
                    <div class="notif-content">
                        <div class="notif-title">${escapeHtml(n.title)}</div>
                        <div class="notif-text">${n.message}</div>
                        <div class="notif-time">${timeAgoSimple(n.created_at)}</div>
                    </div>
                    ${n.is_read ? '' : '<div class="notif-unread-dot"></div>'}
                </div>
            `).join('');
        }

        if (data.unread_count > 0) {
            badge.textContent = data.unread_count;
            badge.style.display = '';
            if (dot) dot.style.display = 'none';
        } else {
            badge.style.display = 'none';
            if (dot) dot.style.display = '';
        }

        notificationsLoaded = true;
    } catch (err) {
        console.error('Notification error:', err);
        list.innerHTML = '<div class="notification-empty">Network error. Try again.</div>';
    }
}

function notifIcon(type) {
    const icons = {
        'info': 'fa-info-circle',
        'warning': 'fa-exclamation-triangle',
        'success': 'fa-check-circle',
        'danger': 'fa-times-circle'
    };
    return icons[type] || 'fa-bell';
}

function timeAgoSimple(datetime) {
    const diff = Math.floor((Date.now() - new Date(datetime).getTime()) / 1000);
    if (diff < 60) return 'Just now';
    if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    return Math.floor(diff / 86400) + 'd ago';
}

function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    if (!dropdown) return;

    dropdown.classList.toggle('active');

    if (dropdown.classList.contains('active') && !notificationsLoaded) {
        loadNotifications();
    }

}

async function markOneRead(id) {
    const item = document.querySelector(`.notification-item[data-id="${id}"]`);
    if (item) {
        item.classList.remove('unread');
        const dot = item.querySelector('.notif-unread-dot');
        if (dot) dot.remove();
    }
    try {
        await fetch(APP_BASE + '/api/mark_notifications_read.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ ids: [id] })
        });
    } catch (err) {
        console.error('Mark read error:', err);
    }
}

async function exportCourses() {
    try {
        const response = await fetch('courses.php');
        const courses = await response.json();
        
        let csv = 'Code,Title,Units,LH,PH,Status\n';
        courses.forEach(c => {
            csv += `${c.code},"${c.title}",${c.credit_units},${c.lecture_hours},${c.practical_hours},${c.status}\n`;
        });
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'courses_2025_2026.csv';
        a.click();
        window.URL.revokeObjectURL(url);
        
        showToast('Courses exported successfully!');
    } catch (error) {
        showToast('Export failed');
    }
}

// Close modal on outside click
document.getElementById('createModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
    if (e.key === 'n' && e.ctrlKey) { e.preventDefault(); openModal(); }
});

// Close sidebar on mobile when clicking outside
document.addEventListener('click', function(e) {
    const overlay = document.getElementById('sidebarOverlay');
    if (overlay && overlay.classList.contains('active') && e.target === overlay) {
        toggleSidebar();
    }
});

// Animate stats on load
window.addEventListener('load', function() {
    const stats = document.querySelectorAll('.stat-value');
    stats.forEach((stat, index) => {
        const finalValue = stat.textContent;
        stat.textContent = '0';
        setTimeout(() => { stat.textContent = finalValue; }, 300 + (index * 100));
    });
});