<?php
// live_sessions.php - Premium Virtual Classroom and Video session workspace using layout templating

require_once 'bootstrap.php';

$pageTitle = "Live Classrooms";
require_once 'layout_header.php';
?>

<div class="page-header animate-in">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 class="page-title">Live Classrooms</h1>
            <p class="page-subtitle">Schedule, manage and launch interactive virtual lecture halls.</p>
        </div>
        <button class="btn btn-primary" onclick="showToast('Class scheduler modal coming soon!')" style="padding: 12px 24px; border-radius: 10px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-calendar-plus"></i> Schedule Session
        </button>
    </div>
</div>

<div class="card animate-in delay-1" style="margin-top: 24px; background: linear-gradient(135deg, #4f46e5, #3b82f6); color: white; border: none;">
    <div style="padding: 32px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 24px;">
        <div style="max-width: 500px;">
            <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Ongoing Session</span>
            <h2 style="font-size: 28px; font-weight: 800; margin-top: 12px; margin-bottom: 8px;">COS 202: Object Oriented Programming Lab</h2>
            <p style="opacity: 0.9; font-size: 15px; line-height: 1.6;">Started 15 minutes ago. 42 students currently in attendance. High participation rate recorded.</p>
        </div>
        <button class="btn" onclick="showToast('Connecting to WebRTC media servers...')" style="background: white; color: #4f46e5; border: none; padding: 14px 28px; border-radius: 12px; font-weight: 700; font-size: 15px; cursor: pointer; box-shadow: 0 10px 20px rgba(0,0,0,0.1); transition: var(--transition);" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            <i class="fas fa-video"></i> Join Classroom
        </button>
    </div>
</div>

<h3 style="margin-top: 32px; font-weight: 700; color: var(--dark);" class="animate-in delay-2">Scheduled Lectures</h3>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-top: 16px;" class="animate-in delay-2">
    <!-- Card 1 -->
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; min-height: 200px;">
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span class="badge badge-primary" style="background: #ecfdf5; color: #059669;">Tomorrow</span>
                <span style="font-size: 13px; color: var(--gray); font-weight: 600;"><i class="far fa-clock"></i> 10:00 AM</span>
            </div>
            <h4 style="font-weight: 700; color: var(--dark); font-size: 16px; margin-bottom: 8px;">MTH 202: Differential Equations Lecture</h4>
            <p style="color: var(--gray); font-size: 14px; line-height: 1.5;">Reviewing Laplace transforms and practical applications in engineering models.</p>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--light-gray); padding-top: 16px; margin-top: 16px;">
            <div style="font-size: 12px; color: var(--gray); font-weight: 500;">Duration: 2 hours</div>
            <a href="#" onclick="showToast('Link copied to clipboard!')" style="color: var(--primary); font-weight: 600; text-decoration: none; font-size: 14px;">Copy Invite Link</a>
        </div>
    </div>

    <!-- Card 2 -->
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; min-height: 200px;">
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <span class="badge badge-primary" style="background: #fef3c7; color: #d97706;">Monday</span>
                <span style="font-size: 13px; color: var(--gray); font-weight: 600;"><i class="far fa-clock"></i> 02:00 PM</span>
            </div>
            <h4 style="font-weight: 700; color: var(--dark); font-size: 16px; margin-bottom: 8px;">HUI-CSC 202: Big Data Analytics Seminar</h4>
            <p style="color: var(--gray); font-size: 14px; line-height: 1.5;">Guest speaker presenting cloud map-reduce clusters and parallel sorting models.</p>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--light-gray); padding-top: 16px; margin-top: 16px;">
            <div style="font-size: 12px; color: var(--gray); font-weight: 500;">Duration: 1.5 hours</div>
            <a href="#" onclick="showToast('Link copied to clipboard!')" style="color: var(--primary); font-weight: 600; text-decoration: none; font-size: 14px;">Copy Invite Link</a>
        </div>
    </div>
</div>

<?php
require_once 'layout_footer.php';
?>
