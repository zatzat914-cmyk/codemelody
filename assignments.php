<?php
// assignments.php - Premium Assignment management portal using layout templating

require_once 'bootstrap.php';

$pageTitle = "Assignments Manager";
require_once 'layout_header.php';
?>

<div class="page-header animate-in">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 class="page-title">Assignments Manager</h1>
            <p class="page-subtitle">Publish homework, review submissions, and manage grading pipelines.</p>
        </div>
        <button class="btn btn-primary" onclick="showToast('Create assignment modal coming soon!')" style="padding: 12px 24px; border-radius: 10px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus"></i> New Assignment
        </button>
    </div>
</div>

<div class="card animate-in delay-1" style="margin-top: 24px;">
    <div class="card-header">
        <h3 class="card-title">Active Assignments</h3>
        <span class="card-action">Sort by Due Date ↓</span>
    </div>
    
    <div style="display: flex; flex-direction: column; gap: 16px; margin-top: 20px;">
        <!-- Assignment 1 -->
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px; border: 1px solid var(--light-gray); border-radius: 12px; transition: var(--transition); flex-wrap: wrap; gap: 16px;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--light-gray)'">
            <div style="display: flex; gap: 16px; align-items: center;">
                <div style="width: 50px; height: 50px; border-radius: 10px; background: rgba(99,102,241,0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas fa-file-code"></i>
                </div>
                <div>
                    <h4 style="font-weight: 700; color: var(--dark); font-size: 16px; margin-bottom: 4px;">COS 202: Data Structures Stack Implementations</h4>
                    <span style="font-size: 13px; color: var(--gray); font-weight: 500;">Due Date: <strong>June 18, 2026</strong></span>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 32px; flex-wrap: wrap;">
                <div style="text-align: center;">
                    <div style="font-size: 18px; font-weight: 700; color: var(--dark);">38 / 42</div>
                    <div style="font-size: 12px; color: var(--gray); font-weight: 500;">Submissions</div>
                </div>
                <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 700;">Active</span>
                <button onclick="showToast('Loading submission list...')" style="background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: var(--transition);">
                    Review
                </button>
            </div>
        </div>

        <!-- Assignment 2 -->
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px; border: 1px solid var(--light-gray); border-radius: 12px; transition: var(--transition); flex-wrap: wrap; gap: 16px;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--light-gray)'">
            <div style="display: flex; gap: 16px; align-items: center;">
                <div style="width: 50px; height: 50px; border-radius: 10px; background: rgba(16,185,129,0.1); color: var(--success); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div>
                    <h4 style="font-weight: 700; color: var(--dark); font-size: 16px; margin-bottom: 4px;">MTH 202: First-Order ODE Problem Set</h4>
                    <span style="font-size: 13px; color: var(--gray); font-weight: 500;">Due Date: <strong>June 22, 2026</strong></span>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 32px; flex-wrap: wrap;">
                <div style="text-align: center;">
                    <div style="font-size: 18px; font-weight: 700; color: var(--dark);">15 / 42</div>
                    <div style="font-size: 12px; color: var(--gray); font-weight: 500;">Submissions</div>
                </div>
                <span class="badge" style="background: #e0f2fe; color: #0369a1; font-weight: 700;">Active</span>
                <button onclick="showToast('Loading submission list...')" style="background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: var(--transition);">
                    Review
                </button>
            </div>
        </div>

        <!-- Assignment 3 -->
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 20px; border: 1px solid var(--light-gray); border-radius: 12px; transition: var(--transition); flex-wrap: wrap; gap: 16px;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='var(--light-gray)'">
            <div style="display: flex; gap: 16px; align-items: center;">
                <div style="width: 50px; height: 50px; border-radius: 10px; background: rgba(245,158,11,0.1); color: var(--warning); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                    <i class="fas fa-archive"></i>
                </div>
                <div>
                    <h4 style="font-weight: 700; color: var(--dark); font-size: 16px; margin-bottom: 4px;">HUI-CSC 202: HDFS Cluster Architecture Report</h4>
                    <span style="font-size: 13px; color: var(--gray); font-weight: 500;">Closed on: <strong>June 10, 2026</strong></span>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 32px; flex-wrap: wrap;">
                <div style="text-align: center;">
                    <div style="font-size: 18px; font-weight: 700; color: var(--dark);">42 / 42</div>
                    <div style="font-size: 12px; color: var(--gray); font-weight: 500;">Submissions</div>
                </div>
                <span class="badge" style="background: #fee2e2; color: #b91c1c; font-weight: 700;">Closed</span>
                <button onclick="showToast('Loading graded results...')" style="background: var(--light-gray); color: var(--dark); border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: var(--transition);">
                    Graded
                </button>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'layout_footer.php';
?>
