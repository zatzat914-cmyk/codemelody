<?php
// analytics.php - Premium Analytics dashboard using layout templating

require_once 'bootstrap.php';

$pageTitle = "Analytics & Insights";
require_once 'layout_header.php';
?>

<div class="page-header animate-in">
    <h1 class="page-title">Analytics & Insights</h1>
    <p class="page-subtitle">Track academic performance, engagement metrics, and class statistics.</p>
</div>

<!-- Stats Overview Grid -->
<div class="stats-grid animate-in delay-1" style="margin-top: 24px;">
    <div class="stat-card students">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
            <div class="stat-trend up"><i class="fas fa-arrow-up"></i> 4.2%</div>
        </div>
        <div class="stat-value">88.5%</div>
        <div class="stat-label">Average Attendance Rate</div>
    </div>
    <div class="stat-card units">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-check-double"></i></div>
            <div class="stat-trend up"><i class="fas fa-arrow-up"></i> 15%</div>
        </div>
        <div class="stat-value">74.2%</div>
        <div class="stat-label">Average Quiz Score</div>
    </div>
    <div class="stat-card courses">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-trend down"><i class="fas fa-arrow-down"></i> 2.1%</div>
        </div>
        <div class="stat-value">4.6 hrs</div>
        <div class="stat-label">Avg. Study Time / Week</div>
    </div>
    <div class="stat-card hours">
        <div class="stat-header">
            <div class="stat-icon"><i class="fas fa-tasks"></i></div>
            <div class="stat-trend up"><i class="fas fa-arrow-up"></i> 98%</div>
        </div>
        <div class="stat-value">92.4%</div>
        <div class="stat-label">Assignment Submission Rate</div>
    </div>
</div>

<div class="two-column animate-in delay-2" style="margin-top: 24px;">
    <!-- Course Performance -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Subject Wise Performance</h3>
            <span class="card-action">This Semester ↓</span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 16px; margin-top: 16px;">
            <div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 600; margin-bottom: 6px;">
                    <span>COS 202 - Computer Programming II</span>
                    <span>82%</span>
                </div>
                <div style="height: 8px; background: var(--light-gray); border-radius: 4px; overflow: hidden;">
                    <div style="width: 82%; height: 100%; background: var(--primary); border-radius: 4px;"></div>
                </div>
            </div>
            <div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 600; margin-bottom: 6px;">
                    <span>MTH 202 - Elementary Differential Equations</span>
                    <span>68%</span>
                </div>
                <div style="height: 8px; background: var(--light-gray); border-radius: 4px; overflow: hidden;">
                    <div style="width: 68%; height: 100%; background: var(--secondary); border-radius: 4px;"></div>
                </div>
            </div>
            <div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 600; margin-bottom: 6px;">
                    <span>HUI-CSC 202 - Big Data Computing</span>
                    <span>91%</span>
                </div>
                <div style="height: 8px; background: var(--light-gray); border-radius: 4px; overflow: hidden;">
                    <div style="width: 91%; height: 100%; background: var(--success); border-radius: 4px;"></div>
                </div>
            </div>
            <div>
                <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 600; margin-bottom: 6px;">
                    <span>IFT 212 - Computer Architecture</span>
                    <span>75%</span>
                </div>
                <div style="height: 8px; background: var(--light-gray); border-radius: 4px; overflow: hidden;">
                    <div style="width: 75%; height: 100%; background: var(--primary); border-radius: 4px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Engagement Chart Mockup -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daily Platform Activity</h3>
            <span class="card-action">Live Stats</span>
        </div>
        <div class="chart-container" style="display: flex; align-items: flex-end; justify-content: space-between; height: 180px; padding-top: 20px;">
            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1;">
                <div style="height: 60px; width: 20px; background: var(--primary-light); border-radius: 4px; transition: var(--transition);" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='var(--primary-light)'"></div>
                <span style="font-size: 12px; color: var(--gray);">Mon</span>
            </div>
            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1;">
                <div style="height: 110px; width: 20px; background: var(--primary-light); border-radius: 4px; transition: var(--transition);" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='var(--primary-light)'"></div>
                <span style="font-size: 12px; color: var(--gray);">Tue</span>
            </div>
            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1;">
                <div style="height: 90px; width: 20px; background: var(--primary-light); border-radius: 4px; transition: var(--transition);" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='var(--primary-light)'"></div>
                <span style="font-size: 12px; color: var(--gray);">Wed</span>
            </div>
            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1;">
                <div style="height: 140px; width: 20px; background: var(--primary-light); border-radius: 4px; transition: var(--transition);" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='var(--primary-light)'"></div>
                <span style="font-size: 12px; color: var(--gray);">Thu</span>
            </div>
            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1;">
                <div style="height: 125px; width: 20px; background: var(--primary-light); border-radius: 4px; transition: var(--transition);" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='var(--primary-light)'"></div>
                <span style="font-size: 12px; color: var(--gray);">Fri</span>
            </div>
            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1;">
                <div style="height: 40px; width: 20px; background: var(--primary-light); border-radius: 4px; transition: var(--transition);" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='var(--primary-light)'"></div>
                <span style="font-size: 12px; color: var(--gray);">Sat</span>
            </div>
            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex: 1;">
                <div style="height: 55px; width: 20px; background: var(--primary-light); border-radius: 4px; transition: var(--transition);" onmouseover="this.style.background='var(--primary)'" onmouseout="this.style.background='var(--primary-light)'"></div>
                <span style="font-size: 12px; color: var(--gray);">Sun</span>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'layout_footer.php';
?>
