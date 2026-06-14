CREATE DATABASE IF NOT EXISTS codemelody CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE codemelody;

DROP TABLE IF EXISTS enrollments;
DROP TABLE IF EXISTS activities;
DROP TABLE IF EXISTS summaries;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('student', 'lecturer', 'admin') DEFAULT 'student',
    avatar_initials VARCHAR(2) DEFAULT 'CM',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL,
    title VARCHAR(200) NOT NULL,
    credit_units INT NOT NULL,
    lecture_hours INT DEFAULT 0,
    practical_hours INT DEFAULT 0,
    status ENUM('compulsory', 'elective', 'required') DEFAULT 'compulsory',
    color_class VARCHAR(20) DEFAULT 'indigo',
    is_paid BOOLEAN DEFAULT FALSE,
    price DECIMAL(10,2) DEFAULT 0.00,
    is_active BOOLEAN DEFAULT TRUE,
    description TEXT,
    video_url VARCHAR(500),
    learning_content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    lecturer_id INT,
    FOREIGN KEY (lecturer_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    matric_no VARCHAR(20) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(255),
    program VARCHAR(50),
    level INT,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    grade VARCHAR(5) DEFAULT NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, course_id)
);

CREATE TABLE activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action_type VARCHAR(50),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE summaries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    content TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_summary (user_id, course_id)
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Password for seeded accounts is: password
INSERT INTO users (email, password, full_name, role, avatar_initials) VALUES
('admin@codemelody.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'CodeMelody Admin', 'admin', 'CA'),
('jane.doe@university.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Jane Doe', 'lecturer', 'JD'),
('sarah@student.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sarah Miller', 'student', 'SM'),
('james@student.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'James Kim', 'student', 'JK'),
('amy@student.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Amy Liu', 'student', 'AL'),
('mark@student.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mark Roberts', 'student', 'MR'),
('emma@student.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Emma Wilson', 'student', 'EW');

INSERT INTO courses (code, title, credit_units, lecture_hours, practical_hours, status, color_class, is_paid, price, lecturer_id) VALUES
('GST 212', 'Philosophy, Logic and Human Existence', 2, 30, 0, 'compulsory', 'indigo', FALSE, 0.00, 2),
('MTH 202', 'Elementary Differential Equations', 2, 30, 0, 'compulsory', 'green', FALSE, 0.00, 2),
('COS 202', 'Computer Programming II', 3, 30, 45, 'compulsory', 'orange', TRUE, 7500.00, 2),
('IFT 212', 'Computer Architecture and Organization', 2, 15, 45, 'compulsory', 'purple', TRUE, 5000.00, 2),
('INS 204', 'Systems Analysis and Design', 3, 30, 45, 'compulsory', 'red', FALSE, 0.00, 2),
('HUI-CSC 202', 'Big Data Computing and Security', 3, 45, 0, 'compulsory', 'teal', TRUE, 12000.00, 2),
('HUI-CSC 204', 'Computing for Community Development', 2, 30, 0, 'compulsory', 'pink', FALSE, 0.00, 2),
('HUI-CSC 210', 'Data Structures and Algorithms', 2, 30, 45, 'compulsory', 'indigo', TRUE, 9000.00, 2),
('HUI-INS 206', 'Management Information Systems', 2, 30, 0, 'elective', 'gray', FALSE, 0.00, 2),
('HUI-CSC 206', 'Computer Hardware Maintenance', 2, 30, 0, 'elective', 'cyan', TRUE, 6500.00, 2),
('HUI-GNS 204', 'Basic Islamic Concept', 1, 0, 0, 'required', 'amber', FALSE, 0.00, 2);

INSERT INTO students (user_id, matric_no, full_name, email, program, level) VALUES
(3, 'U2023/001', 'Sarah Miller', 'sarah@student.edu', 'Computer Science', 200),
(4, 'U2023/002', 'James Kim', 'james@student.edu', 'Computer Science', 200),
(5, 'U2023/003', 'Amy Liu', 'amy@student.edu', 'Information Tech', 200),
(6, 'U2023/004', 'Mark Roberts', 'mark@student.edu', 'Computer Science', 200),
(7, 'U2023/005', 'Emma Wilson', 'emma@student.edu', 'Information Tech', 200);

INSERT INTO enrollments (student_id, course_id) VALUES
(1, 3),
(1, 6),
(2, 2),
(3, 6),
(4, 4),
(5, 5);

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

INSERT INTO activities (user_id, action_type, description) VALUES
(2, 'submission', '<strong>Sarah Miller</strong> submitted Assignment 2 in <strong>COS 202</strong>'),
(2, 'quiz', '<strong>James Kim</strong> scored 92% in <strong>MTH 202</strong> Quiz'),
(2, 'enrollment', '<strong>Amy Liu</strong> enrolled in <strong>HUI-CSC 202</strong>'),
(2, 'question', '<strong>Mark Roberts</strong> asked about <strong>IFT 212</strong> Lab 3'),
(2, 'completion', '<strong>Emma Wilson</strong> completed <strong>INS 204</strong> Project');

INSERT INTO notifications (user_id, title, message, type, is_read) VALUES
(3, 'New Material Available', 'New learning content has been added to <strong>COS 202</strong>. Check it out in your course room.', 'info', FALSE),
(3, 'Assignment Due Soon', 'Your <strong>Big Data Computing</strong> assignment is due in 3 days. Submit on time!', 'warning', FALSE),
(4, 'Quiz Reminder', 'You have an upcoming quiz in <strong>MTH 202</strong> scheduled for next week.', 'info', FALSE),
(5, 'Course Update', '<strong>HUI-CSC 202</strong> has been updated with new video content.', 'info', FALSE),
(5, 'Progress Report', 'You have completed 2 out of 5 topics in <strong>IFT 212</strong>. Keep going!', 'success', FALSE),
(6, 'Enrollment Confirmed', 'You are now enrolled in <strong>COS 202</strong> - Computer Programming II.', 'success', FALSE),
(7, 'New Course Added', '<strong>HUI-CSC 210</strong> - Data Structures and Algorithms is now available.', 'info', FALSE);

UPDATE courses SET learning_content = CONCAT(
    code, ' helps you understand ', title, '. Start by learning the important terms, then connect each idea to examples from class and your daily computing work.\n\n',
    'What you should understand:\n',
    '- The purpose of the course and why it matters.\n',
    '- The core ideas and how they relate to your program.\n',
    '- Practical examples that show how the topic is used.\n',
    '- Questions you should ask when solving related problems.\n\n',
    'After reading, watch the video lesson, take short notes, and summarize the topic in your own words.'
) WHERE learning_content IS NULL;
