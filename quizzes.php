<?php
// quizzes.php - Premium Quiz/Assessment portal using layout templating

require_once 'bootstrap.php';

$pageTitle = "Quizzes & Assessments";
require_once 'layout_header.php';
?>

<div class="page-header animate-in">
    <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 class="page-title">Quizzes & Assessments</h1>
            <p class="page-subtitle">Build customized online test structures and review metrics.</p>
        </div>
        <button class="btn btn-primary" onclick="showToast('Create quiz builder modal coming soon!')" style="padding: 12px 24px; border-radius: 10px; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus-circle"></i> Create Quiz
        </button>
    </div>
</div>

<div class="card animate-in delay-1" style="margin-top: 24px;">
    <div class="card-header">
        <h3 class="card-title">All Quizzes</h3>
        <span class="card-action">Manage Questions →</span>
    </div>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-top: 20px;">
        <!-- Quiz Card 1 -->
        <div style="border: 1px solid var(--light-gray); border-radius: 16px; padding: 24px; display: flex; flex-direction: column; justify-content: space-between; min-height: 250px; transition: var(--transition);" onmouseover="this.style.borderColor='var(--primary)';" onmouseout="this.style.borderColor='var(--light-gray)';">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <span style="font-size: 13px; color: var(--gray); font-weight: 600;"><i class="far fa-clock"></i> 30 mins limit</span>
                    <span class="badge" style="background: #ecfdf5; color: #059669; font-weight: 700;">Live Now</span>
                </div>
                <h4 style="font-weight: 800; color: var(--dark); font-size: 18px; margin-bottom: 8px; line-height: 1.4;">COS 202: Elementary Variables & Loop Control Structures</h4>
                <p style="color: var(--gray); font-size: 14px; line-height: 1.5;">20 Multiple Choice Questions testing variable scope, parameters, and flow logic.</p>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--light-gray); padding-top: 16px; margin-top: 16px;">
                <div style="font-size: 13px; color: var(--dark); font-weight: 600;">32 Responses</div>
                <button onclick="showToast('Loading grades and response sheet...')" style="background: none; border: none; color: var(--primary); font-weight: 700; cursor: pointer; font-size: 14px;">View Grades →</button>
            </div>
        </div>

        <!-- Quiz Card 2 -->
        <div style="border: 1px solid var(--light-gray); border-radius: 16px; padding: 24px; display: flex; flex-direction: column; justify-content: space-between; min-height: 250px; transition: var(--transition);" onmouseover="this.style.borderColor='var(--primary)';" onmouseout="this.style.borderColor='var(--light-gray)';">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <span style="font-size: 13px; color: var(--gray); font-weight: 600;"><i class="far fa-clock"></i> 45 mins limit</span>
                    <span class="badge" style="background: #fef3c7; color: #d97706; font-weight: 700;">Draft Mode</span>
                </div>
                <h4 style="font-weight: 800; color: var(--dark); font-size: 18px; margin-bottom: 8px; line-height: 1.4;">MTH 202: Laplace Transformations Midterm Quiz</h4>
                <p style="color: var(--gray); font-size: 14px; line-height: 1.5;">10 Free Response Questions testing inverse equations and derivatives.</p>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--light-gray); padding-top: 16px; margin-top: 16px;">
                <div style="font-size: 13px; color: var(--dark); font-weight: 600;">0 Responses</div>
                <button onclick="showToast('Launching quiz builder editor...')" style="background: none; border: none; color: var(--primary); font-weight: 700; cursor: pointer; font-size: 14px;">Edit Questions →</button>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'layout_footer.php';
?>
