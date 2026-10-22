-- =========================================================
-- Campus Recruitment Management System (CRMS)
-- Seed Data / Demo Records
-- =========================================================

USE `crms_db`;

-- Standard Bcrypt Hash for 'Admin@123' and 'Student@123'
-- Hash: $2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm (Student@123)
-- Hash: $2y$10$wT8Kk5H0tH0B86Fk1Ym9bO89V7G81c.Oq3v18eXy67xH5q98Y2e8S (Admin@123)

-- ---------------------------------------------------------
-- Insert Administrators
-- ---------------------------------------------------------
INSERT INTO `admins` (`name`, `email`, `password_hash`, `role`) VALUES
('Dr. Alok Nath (Placement Head)', 'admin@crms.edu', '$2y$10$wT8Kk5H0tH0B86Fk1Ym9bO89V7G81c.Oq3v18eXy67xH5q98Y2e8S', 'superadmin'),
('Prof. Sunita Rao (Coordinator)', 'coordinator@crms.edu', '$2y$10$wT8Kk5H0tH0B86Fk1Ym9bO89V7G81c.Oq3v18eXy67xH5q98Y2e8S', 'coordinator');

-- ---------------------------------------------------------
-- Insert Partner Companies
-- ---------------------------------------------------------
INSERT INTO `companies` (`company_id`, `company_name`, `contact_person`, `contact_email`, `contact_phone`, `website`, `industry`, `location`, `description`) VALUES
(1, 'Google India', 'Rohan Mehta', 'campus-india@google.com', '+91 80 6721 8000', 'https://careers.google.com', 'Technology & Internet', 'Bengaluru, Karnataka', 'Global leader in search, cloud, AI, hardware, and digital technologies.'),
(2, 'Microsoft IDC', 'Ananya Deshmukh', 'campus-recruitment@microsoft.com', '+91 40 6695 0000', 'https://careers.microsoft.com', 'Enterprise Software & Cloud', 'Hyderabad, Telangana', 'World pioneer in personal computing, Azure cloud infrastructure, and productivity solutions.'),
(3, 'Amazon Web Services (AWS)', 'Kunal Sen', 'aws-grad-hiring@amazon.com', '+91 80 4152 4000', 'https://amazon.jobs', 'Cloud Computing', 'Bengaluru, Karnataka', 'Comprehensive cloud infrastructure provider driving modern digital transformation.'),
(4, 'Tata Consultancy Services (TCS)', 'Deepak Kulkarni', 'campus.tcs@tcs.com', '+91 22 6778 9999', 'https://www.tcs.com/careers', 'IT Services & Consulting', 'Mumbai, Maharashtra', 'Global multinational IT services, consulting, and business solutions organisation.'),
(5, 'Infosys Technologies', 'Shweta Nair', 'freshers_drive@infosys.com', '+91 80 2852 0261', 'https://www.infosys.com/careers', 'Information Technology', 'Bengaluru, Karnataka', 'Next-generation digital services and consulting firm powering global enterprises.');

-- ---------------------------------------------------------
-- Insert Students
-- ---------------------------------------------------------
INSERT INTO `students` (`student_id`, `name`, `email`, `password_hash`, `phone`, `course`, `semester`, `cgpa`, `skills`, `resume_link`) VALUES
(1, 'Rahul Sharma', 'rahul.sharma@crms.edu', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', '+91 98765 43210', 'B.Tech CSE', 8, 8.85, 'Java, Python, DSA, React, SQL, Spring Boot', 'https://github.com/rahul-crms-resume.pdf'),
(2, 'Priya Patel', 'priya.patel@crms.edu', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', '+91 98765 43211', 'B.Tech IT', 8, 8.20, 'Python, AWS, Cloud, Docker, Linux, SQL', 'https://github.com/priya-patel-resume.pdf'),
(3, 'Amit Verma', 'amit.verma@crms.edu', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', '+91 98765 43212', 'B.Tech ECE', 8, 7.20, 'C++, Embedded Systems, IoT, Python, Linux', 'https://github.com/amit-verma-resume.pdf'),
(4, 'Sneha Reddy', 'sneha.reddy@crms.edu', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', '+91 98765 43213', 'MCA', 4, 7.60, 'PHP, MySQL, Web Development, JavaScript, React', 'https://github.com/sneha-reddy-resume.pdf'),
(5, 'Vikram Singh', 'vikram.singh@crms.edu', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', '+91 98765 43214', 'B.Tech Mechanical', 8, 6.80, 'AutoCAD, SolidWorks, Python, Data Analysis', 'https://github.com/vikram-singh-resume.pdf');

-- ---------------------------------------------------------
-- Insert Jobs / Opportunities
-- ---------------------------------------------------------
INSERT INTO `jobs` (`job_id`, `company_id`, `title`, `description`, `job_type`, `salary_package`, `location`, `min_cgpa`, `allowed_courses`, `allowed_semesters`, `required_skills`, `deadline`, `status`) VALUES
(1, 1, 'Software Engineer (Graduate)', 'Design, test, deploy, maintain and improve large-scale distributed software applications. Strong foundation in Data Structures, Algorithms and System Design.', 'Full-time', '28 LPA', 'Bengaluru / Hyderabad', 8.00, 'B.Tech CSE,B.Tech IT', '7,8', 'DSA, Java, Python, Cloud', '2026-10-15 23:59:59', 'open'),

(2, 2, 'Full Stack Engineering Intern', 'Join Microsoft Core Services Engineering team to build next-gen Azure and Teams web applications using React, TypeScript, and modern backend services.', 'Internship', '50,000 / month', 'Hyderabad, Telangana', 7.50, 'B.Tech CSE,B.Tech IT,MCA', '5,6,7,8', 'React, TypeScript, C#, Node.js', '2026-10-20 23:59:59', 'open'),

(3, 3, 'Cloud Support Associate', 'Provide world-class technical support to AWS global enterprise customers on distributed cloud computing systems, Linux architecture, and networking.', 'Full-time', '14 LPA', 'Bengaluru, Karnataka', 7.00, 'B.Tech CSE,B.Tech IT,B.Tech ECE,MCA', '7,8', 'Linux, Networking, Python, AWS', '2026-10-30 23:59:59', 'open'),

(4, 4, 'Associate Consultant (TCS Digital)', 'Design and build innovative digital solutions in AI, Cloud, Cyber Security, and Big Data for Fortune 500 enterprise clients.', 'Full-time', '7.5 LPA', 'Pan India (Multiple Locations)', 6.50, 'B.Tech CSE,B.Tech IT,B.Tech ECE,MCA', '7,8', 'Python, Web Development, SQL, Git', '2026-11-05 23:59:59', 'open'),

(5, 5, 'Systems Engineer (Infosys SE)', 'Transform legacy business processes into modern digital platforms. Full training provided in Python, Java, and Enterprise RDBMS at the Mysore campus.', 'Full-time', '6.0 LPA', 'Bengaluru / Pune / Mysore', 6.00, 'All', '7,8', 'Java, Python, SQL', '2026-11-15 23:59:59', 'open'),

(6, 4, 'Data Analyst Trainee (TCS Analytics)', 'Work with business intelligence teams to synthesize data sets, design reporting dashboards, and extract strategic predictive metrics.', 'Full-time', '5.5 LPA', 'Pune, Maharashtra', 6.50, 'All', '7,8', 'Excel, SQL, Python, Power BI', '2026-09-01 23:59:59', 'closed');

-- ---------------------------------------------------------
-- Insert Applications
-- ---------------------------------------------------------
INSERT INTO `applications` (`application_id`, `job_id`, `student_id`, `applied_date`, `status`, `notes`) VALUES
(1, 1, 1, '2026-09-02 10:15:00', 'shortlisted', 'Profile verified. Cleared round 1 technical coding assessment.'),
(2, 2, 1, '2026-09-03 11:30:00', 'selected', 'Exceptional performance in technical interview. Offer letter extended.'),
(3, 1, 2, '2026-09-03 14:00:00', 'applied', 'Application submitted, pending resume review.'),
(4, 3, 2, '2026-09-04 09:45:00', 'selected', 'Cleared technical and cloud architecture rounds. Placed.'),
(5, 4, 3, '2026-09-04 16:20:00', 'under_review', 'Shortlisted for aptitude round; awaiting schedule.'),
(6, 5, 3, '2026-09-05 12:10:00', 'shortlisted', 'Technical test cleared. Final HR interview upcoming.'),
(7, 5, 4, '2026-09-05 13:00:00', 'selected', 'Selected in Systems Engineer profile. Mysore training allocated.'),
(8, 4, 4, '2026-09-06 15:30:00', 'applied', 'Application under preliminary eligibility check.');

-- ---------------------------------------------------------
-- Insert Selections (Final outcomes)
-- ---------------------------------------------------------
INSERT INTO `selections` (`selection_id`, `application_id`, `result`, `offer_letter_ref`, `remarks`) VALUES
(1, 2, 'selected', 'MSFT-IND-2026-INT-0441', 'Accepted 6-month internship offer with PPO opportunity.'),
(2, 4, 'selected', 'AWS-BLR-2026-CSA-1092', 'Offered Cloud Support Associate role at AWS Bengaluru at 14 LPA.'),
(3, 7, 'selected', 'INFY-2026-SE-9081', 'Selected in Infosys National Qualifier Test drive at 6.0 LPA.');
