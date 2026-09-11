# Campus Recruitment Management System (CRMS)

A full-stack, role-based web application designed to streamline campus placement activities, automate student eligibility checks, manage corporate recruitment drives, and track candidate selections.

Developed with **PHP**, **MySQL**, **Bootstrap 5**, and modern **Vanilla CSS & JavaScript**. Compatible with standard **XAMPP** environments and standalone PHP web servers.

---

## Key Features & Functional Modules

### 1. Student Portal
- **Authentication & Registration**: Student signup with degree, semester, CGPA, technical skills, and contact information.
- **Dynamic Academic Profile**: Profile editor with completeness indicator and skill tag management.
- **Smart Eligibility Engine**: Real-time evaluation of student profiles against job criteria (CGPA threshold, allowed branches, allowed semesters, application deadlines).
- **Clear Visual Distinction**: Green badges for eligible opportunities (with skill match percentage) and red badges for ineligible drives with specific reasons.
- **1-Click Application Submission**: Instant application submission with cover remarks and automatic prevention of duplicate applications.
- **Visual Application Tracker**: Color-coded 4-stage stepper tracking progress: `Applied` &rarr; `Under Review` &rarr; `Shortlisted` &rarr; `Selected` (or `Not Selected`).
- **Placement Offer Recognition**: Direct display of official offer letter reference codes and coordinator remarks.

### 2. Placement Administrator / Coordinator Portal
- **Executive Dashboard**: Real-time KPI summary cards (Students, Companies, Active Drives, Total Applications, Placed Candidates, Cohort Placement Rate %).
- **Interactive Visual Charts**: Applications received per drive (Bar Chart) and Placements by branch (Donut Chart) powered by Chart.js.
- **Student Directory**: Searchable candidate database with filtering by branch and minimum CGPA.
- **Partner Company Management (CRUD)**: Create, view, update, and remove corporate recruiting profiles.
- **Placement Drive Management (CRUD)**: Create and configure recruitment drives with custom eligibility criteria (min CGPA, multi-branch selection, semesters, skills, CTC, and deadlines).
- **Candidate Shortlisting & Selection Workflow**: Drive-by-drive review board to transition candidates across workflow stages, assign offer letter reference codes, and log remarks.
- **Recruitment Analytics & Reports**: Tabular breakdown of branch-wise placement rates, company hiring statistics, and **CSV export** for official university record keeping.

---

## Default Test Credentials (Pre-seeded)

| Role | Email | Password | Details |
| :--- | :--- | :--- | :--- |
| **Placement Admin** | `admin@crms.edu` | `Admin@123` | Placement Head (Full Administrative Access) |
| **Coordinator** | `coordinator@crms.edu` | `Admin@123` | Placement Coordinator |
| **Student 1** | `rahul.sharma@crms.edu` | `Student@123` | Rahul Sharma (B.Tech CSE, Sem 8, CGPA: 8.85) |
| **Student 2** | `priya.patel@crms.edu` | `Student@123` | Priya Patel (B.Tech IT, Sem 8, CGPA: 8.20) |
| **Student 3** | `amit.verma@crms.edu` | `Student@123` | Amit Verma (B.Tech ECE, Sem 8, CGPA: 7.20) |
| **Student 4** | `sneha.reddy@crms.edu` | `Student@123` | Sneha Reddy (MCA, Sem 4, CGPA: 7.60) |
| **Student 5** | `vikram.singh@crms.edu` | `Student@123` | Vikram Singh (B.Tech Mech, Sem 8, CGPA: 6.80) |

*(Note: In the login page, you can also click the test credentials helper buttons to automatically fill credentials with 1 click!)*

---

## Local Setup Guide

### Method A: Running with XAMPP (Recommended)

1. **Copy or clone the project folder** into your XAMPP web directory:
   ```
   C:\xampp\htdocs\crms
   ```
   *(Or keep it in your current workspace directory and create an Apache alias / symlink)*.

2. **Start Services**:
   - Open the **XAMPP Control Panel**.
   - Click **Start** for **Apache**.
   - Click **Start** for **MySQL**.

3. **Import the Database**:
   - Open your browser and go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
   - Click **Import** tab.
   - Choose `database/schema.sql` and click **Import**.
   - Choose `database/seed.sql` and click **Import**.
   *(Alternatively, run the SQL files via command prompt using `mysql -u root -p < database/schema.sql`)*.

4. **Access CRMS**:
   - Navigate to:
     ```
     http://localhost/crms/
     ```
     *(Or `http://localhost/mini%20project/` depending on your folder name in `htdocs`)*.
   - The root forwarder will automatically direct you to `public/index.php`.

---

### Method B: Running with Standalone PHP (No Apache Needed)

If PHP is installed on your machine and you have MySQL running:

1. Import `database/schema.sql` and `database/seed.sql` into MySQL.
2. From the project root folder, start PHP's built-in web server:
   ```bash
   php -S localhost:8000 -t public
   ```
3. Open your browser and navigate to:
   ```
   http://localhost:8000
   ```

---

## Project Structure

```
crms/
├── config/
│   ├── config.php         # Application constants, CSRF tokens, flash messages, sanitization
│   ├── db.php             # PDO database connection & automatic error recovery
│   └── auth.php           # Role-based access control (Student vs Admin middleware)
├── database/
│   ├── schema.sql         # Database schema (InnoDB, constraints, foreign keys, indexes)
│   └── seed.sql           # Realistic seed dataset (Admins, Companies, Jobs, Applications)
├── app/
│   ├── models/
│   │   ├── User.php       # Authentication & credential verification
│   │   ├── Student.php    # Student profile CRUD & academic directory
│   │   ├── Company.php    # Corporate recruiter CRUD & stats
│   │   ├── Job.php        # Job drives & automated eligibility engine
│   │   ├── Application.php# Application submission, duplicate prevention, status updates
│   │   ├── Selection.php  # Placement outcome logging & offer letter tracking
│   │   └── Report.php     # Placement KPIs, branch breakdowns, company metrics
│   ├── controllers/
│   │   ├── AuthController.php        # Unified login, student registration, logout
│   │   ├── StudentController.php     # Dashboard, profile edit, job browsing, apply
│   │   ├── ApplicationController.php # Status tracking stepper, withdrawal
│   │   └── AdminController.php       # Company CRUD, drive CRUD, shortlisting, reports
│   └── views/
│       ├── layouts/
│       │   ├── header.php            # Responsive navbar, student/admin dynamic menus, alerts
│       │   └── footer.php            # Footer, Chart.js CDN, modals, scripts
│       ├── auth/
│       │   ├── login.php             # Modern login portal with quick demo credential fillers
│       │   └── register.php          # Comprehensive student registration form
│       ├── student/
│       │   ├── dashboard.php         # Welcome hero, KPI cards, recommended drives
│       │   ├── profile.php           # Academic profile editor with completeness bar
│       │   ├── jobs.php              # Filterable job board with automated eligibility badges
│       │   ├── job_detail.php        # Drive details with side-by-side criteria audit table
│       │   └── applications.php      # 4-stage visual timeline stepper & offer status
│       └── admin/
│           ├── dashboard.php         # Recruitment metrics, Bar Chart & Donut Chart
│           ├── students.php          # Student directory with search, branch, and CGPA filter
│           ├── companies.php         # Corporate partner directory with Add/Edit modals
│           ├── jobs.php              # Placement drive management with applicant counts
│           ├── job_form.php          # Drive creator with visual eligibility rules builder
│           ├── applications.php      # Applicant review board with modal status updates
│           └── reports.php           # Branch analytics, company statistics, and CSV export
├── public/
│   ├── index.php          # Front controller & request router
│   └── assets/
│       ├── css/style.css  # Custom modern styling, status pills, glassmorphism, cards
│       └── js/main.js     # Modal data binding, table filters, auto-dismiss alerts
├── docs/
│   ├── ER_DIAGRAM.md      # Detailed Mermaid Entity-Relationship diagram & Data Dictionary
│   └── ARCHITECTURE.md   # Tiered Architecture & Sequence flow diagrams
├── index.php              # Root forwarder to public/index.php
└── README.md              # Documentation & setup guide
```

---

## Security Highlights

- **Prepared Statements**: 100% of queries use PDO parameterized statements to eradicate SQL Injection.
- **Password Security**: Passwords hashed using standard `password_hash()` with `PASSWORD_BCRYPT`.
- **Cross-Site Request Forgery (CSRF)**: Cryptographically secure tokens checked on all state-altering POST requests.
- **Cross-Site Scripting (XSS)**: Strict escaping of all output via `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
- **Role-Based Access Control (RBAC)**: Strict server-side route guarding preventing unauthorized access to admin operations even if direct URLs are guessed.
