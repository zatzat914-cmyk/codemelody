-- CodeMelody Database Upgrade: Add notifications & summaries tables
-- Run this AFTER the main schema if upgrading an existing install

CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_read (user_id, is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS summaries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    content TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_summary (user_id, course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed notifications for existing students
INSERT INTO notifications (user_id, title, message, type, is_read) VALUES
(3, 'New Material Available', 'New learning content has been added to <strong>COS 202</strong>. Check it out in your course room.', 'info', FALSE),
(3, 'Assignment Due Soon', 'Your <strong>Big Data Computing</strong> assignment is due in 3 days. Submit on time!', 'warning', FALSE),
(4, 'Quiz Reminder', 'You have an upcoming quiz in <strong>MTH 202</strong> scheduled for next week.', 'info', FALSE),
(5, 'Course Update', '<strong>HUI-CSC 202</strong> has been updated with new video content.', 'info', FALSE),
(5, 'Progress Report', 'You have completed 2 out of 5 topics in <strong>IFT 212</strong>. Keep going!', 'success', FALSE),
(6, 'Enrollment Confirmed', 'You are now enrolled in <strong>COS 202</strong> - Computer Programming II.', 'success', FALSE),
(7, 'New Course Added', '<strong>HUI-CSC 210</strong> - Data Structures and Algorithms is now available.', 'info', FALSE);
