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
(5, 'Infosys Technologies', 'Shweta Nair', 'freshers_drive@infosys.com', '+91 80 2852 0261', 'https://www.infosys.com/careers', 'Information Technology', 'Bengaluru, Karnataka', 'Next-generation digital services and consulting firm powering global enterprises.'),
(6, 'Apple India', 'Aditi Sharma', 'campus_apple@apple.com', '+91 40 6789 1234', 'https://apple.com/careers', 'Consumer Electronics & Software', 'Hyderabad, Telangana', 'Innovator in consumer electronics, iOS platforms, custom silicon chips, and digital services.'),
(7, 'Meta Platforms', 'Siddharth Roy', 'grad-recruiting@meta.com', '+91 124 4567 890', 'https://metacareers.com', 'Social Technologies & AI', 'Gurugram, Haryana', 'Connecting billions of people worldwide through cutting-edge AR/VR, AI, and messaging infrastructure.'),
(8, 'NVIDIA Graphics', 'Vikas Grover', 'campus-india@nvidia.com', '+91 20 6678 9000', 'https://nvidia.com/careers', 'GPU & Accelerated AI', 'Pune, Maharashtra', 'Global pioneer of GPU computing, LLM accelerators, autonomous machines, and Omniverse simulations.'),
(9, 'Intel Technology', 'Pooja Hegde', 'india.campus@intel.com', '+91 80 2507 5000', 'https://intel.com/jobs', 'Semiconductors & Silicon', 'Bengaluru, Karnataka', 'Pioneer in processor microarchitecture, silicon fabrication, edge computing, and AI hardware.'),
(10, 'Oracle India', 'Arjun Kapoor', 'oracle_campus@oracle.com', '+91 80 4107 6000', 'https://oracle.com/careers', 'Database & Enterprise Cloud', 'Bengaluru, Karnataka', 'Architect of autonomous databases, enterprise ERP software, and Oracle Cloud Infrastructure (OCI).'),
(11, 'Cisco Systems', 'Neha Gupta', 'grad-cisco@cisco.com', '+91 80 4426 0000', 'https://cisco.com/careers', 'Networking & Cybersecurity', 'Bengaluru, Karnataka', 'Worldwide tech leader in networking architecture, SD-WAN, unified collaboration, and cybersecurity.'),
(12, 'Adobe Systems', 'Sameer Saxena', 'campus-india@adobe.com', '+91 120 244 4711', 'https://adobe.com/careers', 'Digital Media & Creative Cloud', 'Noida, Uttar Pradesh', 'Transforming digital experiences through Photoshop, Illustrator, Premiere, Document Cloud, and generative AI.'),
(13, 'Salesforce India', 'Tanvi Mathur', 'campus_salesforce@salesforce.com', '+91 40 6712 3456', 'https://salesforce.com/careers', 'Cloud CRM & Enterprise AI', 'Hyderabad, Telangana', 'World #1 CRM platform delivering Customer 360, Agentforce AI, and automated enterprise workflows.'),
(14, 'IBM India', 'Rajesh Balan', 'campus_ibm@ibm.com', '+91 80 4068 3000', 'https://ibm.com/careers', 'Hybrid Cloud & Enterprise AI', 'Bengaluru, Karnataka', 'Global catalyst for open hybrid cloud architectures with Red Hat OpenShift, watsonx, and enterprise IT.'),
(15, 'Qualcomm India', 'Kavita Iyer', 'qualcomm_grads@qualcomm.com', '+91 40 6608 1000', 'https://qualcomm.com/careers', 'Wireless Tech & 5G Chipsets', 'Hyderabad, Telangana', 'World leader in 5G wireless innovation, Snapdragon processors, RF modems, and edge AI compute.'),
(16, 'Goldman Sachs', 'Varun Khurana', 'campus-gs@gs.com', '+91 80 4127 8000', 'https://goldmansachs.com/careers', 'Investment Banking & Fintech', 'Bengaluru, Karnataka', 'Leading global investment banking and securities firm building low-latency algorithmic trading systems.'),
(17, 'Morgan Stanley', 'Meera Joshi', 'india_recruiting@morganstanley.com', '+91 22 6118 1000', 'https://morganstanley.com/careers', 'Global Financial Services', 'Mumbai, Maharashtra', 'Prestigious financial services powerhouse managing algorithmic assets, fintech platforms, and risk analytics.'),
(18, 'JP Morgan Chase', 'Nikhil Bajaj', 'jpmc_gradhiring@jpmchase.com', '+91 80 6767 1000', 'https://jpmorgan.com/careers', 'Financial Technology & Banking', 'Bengaluru, Karnataka', 'Premier financial institution engineering distributed blockchain networks, high-frequency payments, and cloud core.'),
(19, 'Wipro Limited', 'Rashmi Menon', 'freshers.wipro@wipro.com', '+91 80 2844 0011', 'https://wipro.com/careers', 'IT Consulting & Services', 'Bengaluru, Karnataka', 'Leading technology services and consulting company focused on building innovative cognitive computing solutions.'),
(20, 'Accenture Solutions', 'Manish Chawla', 'campus.accenture@accenture.com', '+91 80 6750 0000', 'https://accenture.com/careers', 'Strategy & Digital Consulting', 'Bengaluru, Karnataka', 'Global professional services titan providing cloud migration, cybersecurity, and digital business transformation.'),
(21, 'Cognizant Technology', 'Preeti Sundaram', 'cognizant_campus@cognizant.com', '+91 44 4209 6000', 'https://cognizant.com/careers', 'Digital Transformation & Cloud', 'Chennai, Tamil Nadu', 'Engineering modern businesses with generative AI, legacy modernization, and IoT-driven architectures.'),
(22, 'Capgemini India', 'Harish Prabhu', 'campus.capgemini@capgemini.com', '+91 22 6755 7000', 'https://capgemini.com/careers', 'Consulting & Engineering', 'Mumbai, Maharashtra', 'Global business and technology transformation partner navigating custom cloud, data, and intelligent industry.'),
(23, 'Deloitte India', 'Ritu Chopra', 'deloitte_campus@deloitte.com', '+91 40 7111 8000', 'https://deloitte.com/careers', 'Audit, Consulting & Advisory', 'Hyderabad, Telangana', 'World largest professional services network empowering enterprises in strategy, risk advisory, and tax tech.'),
(24, 'Larsen & Toubro (L&T)', 'Girish Mahajan', 'lnt_campus@larsentoubro.com', '+91 22 6752 5656', 'https://larsentoubro.com/careers', 'Core Engineering & Infra', 'Mumbai, Maharashtra', 'Indian multinational conglomerate engaged in EPC projects, defense engineering, smart cities, and heavy manufacturing.'),
(25, 'Samsung R&D Institute', 'Divya Raman', 'samsung_campus@samsung.com', '+91 120 405 5000', 'https://samsung.com/careers', 'Consumer Tech & Mobile R&D', 'Noida, Uttar Pradesh', 'Top tech R&D hub developing camera vision AI, Galaxy mobile software, SmartThings IoT, and 6G communications.'),
(26, 'Texas Instruments', 'Anand Kulkarni', 'ti_recruiting@ti.com', '+91 80 2509 9000', 'https://ti.com/careers', 'Analog Chips & Embedded Processing', 'Bengaluru, Karnataka', 'Global semiconductor company designing analog chips and embedded processors powering automotive and industrial automation.'),
(27, 'Uber India', 'Sahil Merchant', 'campus-uber@uber.com', '+91 40 6734 5000', 'https://uber.com/careers', 'Mobility & Distributed Systems', 'Hyderabad, Telangana', 'Pioneer of urban ride-sharing, marketplace pricing algorithms, maps engineering, and high-scale microservices.'),
(28, 'Atlassian India', 'Kriti Mathur', 'grad-atlassian@atlassian.com', '+91 80 6730 0000', 'https://atlassian.com/careers', 'Collaboration & Dev Software', 'Bengaluru, Karnataka', 'Creator of Jira, Confluence, Trello, and Bitbucket empowering agile software teams and DevOps workflows.')
ON DUPLICATE KEY UPDATE 
company_name = VALUES(company_name),
contact_person = VALUES(contact_person),
contact_email = VALUES(contact_email),
website = VALUES(website),
industry = VALUES(industry),
location = VALUES(location),
description = VALUES(description);

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
