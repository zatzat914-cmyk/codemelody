// Global utility functions

// AJAX helper
async function fetchAPI(url, options = {}) {
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json'
        }
    };
    
    try {
        const response = await fetch(url, { ...defaultOptions, ...options });
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Request failed');
        }
        
        return data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

// Form validation
function validateForm(formElement) {
    const inputs = formElement.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.style.borderColor = '#ef4444';
            
            // Add error message
            let errorMsg = input.parentElement.querySelector('.error-message');
            if (!errorMsg) {
                errorMsg = document.createElement('span');
                errorMsg.className = 'error-message';
                errorMsg.style.cssText = 'color: #ef4444; font-size: 12px; margin-top: 4px; display: block;';
                input.parentElement.appendChild(errorMsg);
            }
            errorMsg.textContent = 'This field is required';
        } else {
            input.style.borderColor = '#e2e8f0';
            const errorMsg = input.parentElement.querySelector('.error-message');
            if (errorMsg) errorMsg.remove();
        }
    });
    
    return isValid;
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Search functionality
function setupSearch(inputSelector, itemsSelector, searchKey) {
    const searchInput = document.querySelector(inputSelector);
    const items = document.querySelectorAll(itemsSelector);
    
    if (!searchInput) return;
    
    searchInput.addEventListener('input', debounce((e) => {
        const query = e.target.value.toLowerCase();
        
        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(query) ? '' : 'none';
        });
    }, 300));
}

// Confirm action
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Format date
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// Format number
function formatNumber(num) {
    return new Intl.NumberFormat().format(num);
}

// Copy to clipboard
async function copyToClipboard(text) {
    try {
        await navigator.clipboard.writeText(text);
        showToast('Copied to clipboard!');
    } catch (err) {
        showToast('Failed to copy');
    }
}

// Course search with dropdown
function setupGlobalSearch() {
    const input = document.getElementById('searchInput');
    const dropdown = document.getElementById('searchDropdown');
    if (!input || !dropdown) return;

    const search = debounce(async function() {
        const query = input.value.trim();

        if (query.length < 1) {
            dropdown.classList.remove('active');
            dropdown.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(APP_BASE + '/search_courses.php?q=' + encodeURIComponent(query));
            const data = await response.json();

            if (data.success && data.results.length > 0) {
                dropdown.innerHTML = data.results.map(c => `
                    <a class="search-dropdown-item" href="${APP_BASE}/courses/learn.php?id=${c.id}">
                        <div class="search-dropdown-icon">${escapeHtml(c.code).charAt(0)}</div>
                        <div class="search-dropdown-info">
                            <span class="search-dropdown-code">${escapeHtml(c.code)}</span>
                            <span class="search-dropdown-title">${escapeHtml(c.title)}</span>
                        </div>
                    </a>
                `).join('');
                dropdown.classList.add('active');
            } else {
                dropdown.innerHTML = `<div class="search-dropdown-empty">No courses found matching your search.</div>`;
                dropdown.classList.add('active');
            }
        } catch (err) {
            console.error('Search error:', err);
        }
    }, 250);

    input.addEventListener('input', search);

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            const query = input.value.trim();
            if (query) {
                dropdown.classList.remove('active');
                window.location = APP_BASE + '/courses/explore.php?q=' + encodeURIComponent(query);
            }
        }
        if (e.key === 'Escape') {
            dropdown.classList.remove('active');
            input.blur();
        }
    });

    document.addEventListener('click', (e) => {
        const searchBar = document.getElementById('globalSearch');
        if (searchBar && !searchBar.contains(e.target)) {
            dropdown.classList.remove('active');
        }
    });
}

function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// Click outside to close dropdowns
document.addEventListener('click', function(e) {
    const notifWrapper = document.getElementById('notificationWrapper');
    if (notifWrapper && !notifWrapper.contains(e.target)) {
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown) dropdown.classList.remove('active');
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    setupGlobalSearch();
    
    // Add form validation to all forms
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!validateForm(form)) {
                e.preventDefault();
            }
        });
    });
});