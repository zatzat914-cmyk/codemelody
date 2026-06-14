<?php
// resources.php - Premium Course Resources and File Downloader using layout templating

require_once 'bootstrap.php';

$pageTitle = "Resource Repository";
require_once 'layout_header.php';
?>

<div class="page-header animate-in">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 class="page-title">Resource Repository</h1>
            <p class="page-subtitle">Upload slides, syllabi, code snippets, and study materials.</p>
        </div>
        <button class="btn btn-primary" onclick="showToast('Uploader tool panel coming soon!')" style="padding: 12px 24px; border-radius: 10px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-file-upload"></i> Upload Files
        </button>
    </div>
</div>

<div class="card animate-in delay-1" style="margin-top: 24px;">
    <div class="card-header">
        <h3 class="card-title">Shared Materials</h3>
        <span class="card-action">New Folder +</span>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
        <!-- Folder 1 -->
        <div style="border: 1px solid var(--light-gray); border-radius: 12px; padding: 20px; text-align: center; cursor: pointer; transition: var(--transition);" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='var(--light-gray)'; this.style.transform='translateY(0)';">
            <i class="fas fa-folder" style="font-size: 48px; color: #f59e0b; margin-bottom: 12px;"></i>
            <h4 style="font-weight: 700; color: var(--dark); font-size: 15px; margin-bottom: 4px;">Lecture Slides</h4>
            <span style="font-size: 12px; color: var(--gray);">18 files • 48.2 MB</span>
        </div>

        <!-- Folder 2 -->
        <div style="border: 1px solid var(--light-gray); border-radius: 12px; padding: 20px; text-align: center; cursor: pointer; transition: var(--transition);" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='var(--light-gray)'; this.style.transform='translateY(0)';">
            <i class="fas fa-folder" style="font-size: 48px; color: #10b981; margin-bottom: 12px;"></i>
            <h4 style="font-weight: 700; color: var(--dark); font-size: 15px; margin-bottom: 4px;">Syllabus & Guides</h4>
            <span style="font-size: 12px; color: var(--gray);">3 files • 2.5 MB</span>
        </div>

        <!-- Folder 3 -->
        <div style="border: 1px solid var(--light-gray); border-radius: 12px; padding: 20px; text-align: center; cursor: pointer; transition: var(--transition);" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='var(--light-gray)'; this.style.transform='translateY(0)';">
            <i class="fas fa-folder" style="font-size: 48px; color: #6366f1; margin-bottom: 12px;"></i>
            <h4 style="font-weight: 700; color: var(--dark); font-size: 15px; margin-bottom: 4px;">Source Code Files</h4>
            <span style="font-size: 12px; color: var(--gray);">12 files • 1.1 MB</span>
        </div>

        <!-- Folder 4 -->
        <div style="border: 1px solid var(--light-gray); border-radius: 12px; padding: 20px; text-align: center; cursor: pointer; transition: var(--transition);" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='var(--light-gray)'; this.style.transform='translateY(0)';">
            <i class="fas fa-folder" style="font-size: 48px; color: #ef4444; margin-bottom: 12px;"></i>
            <h4 style="font-weight: 700; color: var(--dark); font-size: 15px; margin-bottom: 4px;">Past Exams</h4>
            <span style="font-size: 12px; color: var(--gray);">8 files • 12.8 MB</span>
        </div>
    </div>
</div>

<?php
require_once 'layout_footer.php';
?>
