# Campus Recruitment Management System (CRMS)
## System Architecture & Sequence Workflows

This document outlines the layered software architecture, security mechanisms, and end-to-end user workflows of CRMS.

---

### 1. High-Level Tiered Architecture

```mermaid
graph TD
    subgraph Client ["Presentation Layer (Client)"]
        UI_Desktop["Desktop / Laptop Browser"]
        UI_Mobile["Mobile / Tablet Browser"]
        UI_Assets["HTML5 / Bootstrap 5 / CSS3 / Vanilla JS"]
    end

    subgraph Entry ["Web Server & Routing Layer"]
        FC["Front Controller (public/index.php)"]
        AUTH_GUARD["Authentication & CSRF Middleware (config/auth.php)"]
    end

    subgraph Controllers ["Application Controllers Layer"]
        C_AUTH["AuthController"]
        C_STUDENT["StudentController"]
        C_APP["ApplicationController"]
        C_ADMIN["AdminController"]
    end

    subgraph BusinessLogic ["Domain & Model Layer"]
        M_USER["User (Auth / Hashes)"]
        M_STUDENT["Student (Profiles)"]
        M_COMPANY["Company (Partner Profiles)"]
        M_JOB["Job (Drives & Eligibility Engine)"]
        M_APP["Application (State Machine)"]
        M_REPORT["Report (Analytics & Aggregations)"]
    end

    subgraph Storage ["Persistence & Data Layer"]
        DB[(MySQL / MariaDB Database)]
        PDO["PDO Layer (Prepared Statements)"]
    end

    UI_Desktop --> FC
    UI_Mobile --> FC
    FC --> AUTH_GUARD
    AUTH_GUARD --> C_AUTH
    AUTH_GUARD --> C_STUDENT
    AUTH_GUARD --> C_APP
    AUTH_GUARD --> C_ADMIN

    C_AUTH --> M_USER
    C_STUDENT --> M_STUDENT
    C_STUDENT --> M_JOB
    C_APP --> M_APP
    C_ADMIN --> M_COMPANY
    C_ADMIN --> M_JOB
    C_ADMIN --> M_APP
    C_ADMIN --> M_REPORT

    M_USER --> PDO
    M_STUDENT --> PDO
    M_COMPANY --> PDO
    M_JOB --> PDO
    M_APP --> PDO
    M_REPORT --> PDO

    PDO --> DB
```

---

### 2. End-to-End Sequence Workflows

#### Flow A: Student Automated Eligibility Check & Application Submission

```mermaid
sequenceDiagram
    autonumber
    actor Student as Student (Candidate)
    participant Browser as Web Browser
    participant Router as Front Controller (public/index.php)
    participant StudentCtrl as StudentController
    participant JobModel as Job & Eligibility Engine
    participant AppModel as Application Model
    participant DB as MySQL Database

    Student->>Browser: Navigates to Job Opportunities
    Browser->>Router: GET ?page=student/jobs
    Router->>StudentCtrl: jobs()
    StudentCtrl->>JobModel: getAll(['active_only' => true])
    JobModel->>DB: SELECT * FROM jobs WHERE status='open' AND deadline >= NOW()
    DB-->>JobModel: Returns open placement drives
    
    loop For each job drive
        StudentCtrl->>JobModel: checkEligibility(job, studentProfile)
        Note over JobModel: Compares CGPA, Allowed Courses,<br/>Semesters, and Skill Match %
        JobModel-->>StudentCtrl: Returns [is_eligible, reasons, match_pct]
    end

    StudentCtrl-->>Browser: Renders Job Board with Eligibility Badges
    Student->>Browser: Clicks "Apply" for eligible job
    Browser->>Router: POST ?page=student/apply (job_id, csrf_token, notes)
    Router->>StudentCtrl: apply()
    StudentCtrl->>JobModel: checkEligibility(job, studentProfile)
    
    alt If Ineligible
        StudentCtrl-->>Browser: Flash Error: Criteria not satisfied
    else If Eligible & Not previously applied
        StudentCtrl->>AppModel: apply(jobId, studentId, notes)
        AppModel->>DB: INSERT INTO applications (job_id, student_id, status) VALUES (?, ?, 'applied')
        DB-->>AppModel: Success (application_id)
        StudentCtrl-->>Browser: Redirects to ?page=student/applications with success toast
    end
```

#### Flow B: Placement Administrator Shortlisting & Selection Flow

```mermaid
sequenceDiagram
    autonumber
    actor Admin as Placement Coordinator
    participant Browser as Web Browser
    participant Router as Front Controller
    participant AdminCtrl as AdminController
    participant AppModel as Application Model
    participant SelectionModel as Selection Model
    participant DB as MySQL Database

    Admin->>Browser: Opens Applications Review for a Drive
    Browser->>Router: GET ?page=admin/applications&job_id=1
    Router->>AdminCtrl: applications()
    AdminCtrl->>AppModel: getByJob(jobId, statusFilter)
    AppModel->>DB: SELECT * FROM applications JOIN students WHERE job_id = 1
    DB-->>AppModel: Returns list of candidates with CGPA and skills
    AdminCtrl-->>Browser: Renders candidate review board

    Admin->>Browser: Selects candidate -> Marks "Selected" -> Enters Offer Ref
    Browser->>Router: POST ?page=admin/application-update (app_id, status='selected', offer_ref, notes)
    Router->>AdminCtrl: updateApplicationStatus()
    AdminCtrl->>AppModel: updateStatus(appId, 'selected', notes, offerRef)
    AppModel->>DB: UPDATE applications SET status = 'selected', notes = ? WHERE application_id = ?
    AppModel->>SelectionModel: recordResult(appId, 'selected', offerRef, notes)
    SelectionModel->>DB: INSERT INTO selections (application_id, result, offer_letter_ref, remarks)
    DB-->>AdminCtrl: Success
    AdminCtrl-->>Browser: Flash success message & refreshed candidate list
```

---

### 3. Core Security & Design Principles

1. **Role-Based Access Control (RBAC)**:
   - Evaluated on every controller invocation.
   - `Auth::requireStudent()` ensures administrators cannot unintentionally alter student state.
   - `Auth::requireAdmin()` strictly guards administrative operations (managing companies, editing drives, selecting candidates, exporting CSV reports).

2. **Defense Against SQL Injection**:
   - 100% of database interactions use PDO with parameterized queries and prepared statements (`?` place-holders).
   - PDO emulation of prepared statements is explicitly disabled (`PDO::ATTR_EMULATE_PREPARES => false`).

3. **Cross-Site Request Forgery (CSRF) Tokens**:
   - Cryptographically random 32-byte hexadecimal tokens generated via `random_bytes(32)`.
   - Included in every state-altering `<form>` via `<?= csrfField() ?>`.
   - Verified before processing POST requests using timing-attack resistant `hash_equals()`.

4. **Cross-Site Scripting (XSS) Prevention**:
   - All dynamic output rendered to views is sanitized using `e()` helper (`htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`).

5. **Secure Credential Storage**:
   - Passwords hashed using industry-standard `PASSWORD_BCRYPT` with automated salt management. Plaintext passwords are never persisted.
