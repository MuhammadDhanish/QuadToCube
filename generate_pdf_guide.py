import os
import sys
from reportlab.lib.pagesizes import letter
from reportlab.lib import colors
from reportlab.lib.units import inch
from reportlab.platypus import (
    SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle, PageBreak, KeepTogether, HRFlowable
)
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.enums import TA_CENTER, TA_LEFT, TA_RIGHT, TA_JUSTIFY
from reportlab.pdfgen import canvas

class NumberedCanvas(canvas.Canvas):
    def __init__(self, *args, **kwargs):
        super(NumberedCanvas, self).__init__(*args, **kwargs)
        self._saved_page_states = []

    def showPage(self):
        self._saved_page_states.append(dict(self.__dict__))
        self._startPage()

    def save(self):
        num_pages = len(self._saved_page_states)
        for state in self._saved_page_states:
            self.__dict__.update(state)
            self.draw_page_decorations(num_pages)
            super(NumberedCanvas, self).showPage()
        super(NumberedCanvas, self).save()

    def draw_page_decorations(self, page_count):
        self.saveState()
        self.setFont("Helvetica", 8)
        self.setFillColor(colors.HexColor("#64748b"))
        
        # Header (pages > 1)
        if self._pageNumber > 1:
            self.drawString(54, letter[1] - 36, "Campus Recruitment Management System (CRMS) — Technical Reference Guide")
            self.setStrokeColor(colors.HexColor("#cbd5e1"))
            self.setLineWidth(0.5)
            self.line(54, letter[1] - 42, letter[0] - 54, letter[1] - 42)
            
        # Footer
        page_text = f"Page {self._pageNumber} of {page_count}"
        self.drawRightString(letter[0] - 54, 32, page_text)
        self.drawString(54, 32, "Confidential — Academic & Placement Project Documentation")
        self.setStrokeColor(colors.HexColor("#cbd5e1"))
        self.setLineWidth(0.5)
        self.line(54, 44, letter[0] - 54, 44)
        
        self.restoreState()

def build_pdf(filename):
    doc = SimpleDocTemplate(
        filename,
        pagesize=letter,
        leftMargin=54,
        rightMargin=54,
        topMargin=54,
        bottomMargin=54
    )
    
    styles = getSampleStyleSheet()
    
    # Custom styles
    title_style = ParagraphStyle(
        'DocTitle',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=24,
        leading=28,
        textColor=colors.HexColor('#0f172a'),
        alignment=TA_CENTER
    )
    
    subtitle_style = ParagraphStyle(
        'DocSubtitle',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=12,
        leading=16,
        textColor=colors.HexColor('#475569'),
        alignment=TA_CENTER
    )
    
    h1_style = ParagraphStyle(
        'Heading1_Custom',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=15,
        leading=19,
        textColor=colors.HexColor('#1e3a8a'),
        spaceBefore=14,
        spaceAfter=6
    )
    
    h2_style = ParagraphStyle(
        'Heading2_Custom',
        parent=styles['Normal'],
        fontName='Helvetica-Bold',
        fontSize=12,
        leading=15,
        textColor=colors.HexColor('#0f766e'),
        spaceBefore=10,
        spaceAfter=4
    )
    
    body_style = ParagraphStyle(
        'Body_Custom',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=9.5,
        leading=14,
        textColor=colors.HexColor('#334155'),
        alignment=TA_LEFT
    )
    
    code_style = ParagraphStyle(
        'Code_Custom',
        parent=styles['Normal'],
        fontName='Courier',
        fontSize=8,
        leading=11,
        textColor=colors.HexColor('#0f172a')
    )
    
    callout_style = ParagraphStyle(
        'Callout_Text',
        parent=styles['Normal'],
        fontName='Helvetica',
        fontSize=9,
        leading=13,
        textColor=colors.HexColor('#1e293b')
    )

    story = []

    # ------------------ COVER / HEADER ------------------
    story.append(Spacer(1, 10))
    story.append(Paragraph("Campus Recruitment Management System", title_style))
    story.append(Spacer(1, 4))
    story.append(Paragraph("<b>(CRMS)</b> System Architecture, Module Breakdown & Code Guide", subtitle_style))
    story.append(Spacer(1, 12))
    story.append(HRFlowable(width="100%", thickness=2, color=colors.HexColor('#2563eb'), spaceBefore=0, spaceAfter=14))

    # Meta Info Table
    meta_data = [
        [
            Paragraph("<b>Project:</b> CRMS (Mini Project)", body_style),
            Paragraph("<b>Stack:</b> PHP 8.2 (MVC) + MySQL / MariaDB", body_style)
        ],
        [
            Paragraph("<b>Frontend:</b> Bootstrap 5.3 + Vanilla CSS", body_style),
            Paragraph("<b>Server:</b> Apache (XAMPP) / Port 80 & 3306", body_style)
        ],
        [
            Paragraph("<b>Database:</b> crms_db (6 Relational Tables)", body_style),
            Paragraph("<b>Auth:</b> Role-based (Admin, Coordinator, Student)", body_style)
        ]
    ]
    t_meta = Table(meta_data, colWidths=[240, 260])
    t_meta.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor('#f8fafc')),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor('#e2e8f0')),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor('#e2e8f0')),
        ('TOPPADDING', (0,0), (-1,-1), 5),
        ('BOTTOMPADDING', (0,0), (-1,-1), 5),
        ('LEFTPADDING', (0,0), (-1,-1), 8),
        ('RIGHTPADDING', (0,0), (-1,-1), 8),
    ]))
    story.append(t_meta)
    story.append(Spacer(1, 14))

    # ------------------ SECTION 1 ------------------
    story.append(Paragraph("1. Configuration & Database Layer (config/)", h1_style))
    story.append(Paragraph(
        "The foundation of CRMS consists of <b>config.php</b> and <b>db.php</b>. These handle security initialization, environment detection, and the PDO database connection singleton.",
        body_style
    ))
    story.append(Spacer(1, 6))

    story.append(Paragraph("A. Global Config & Security (config/config.php)", h2_style))
    story.append(Paragraph(
        "• <b>Session & Cookie Sync:</b> Implements secure HMAC-SHA256 signed session cookie tracking.<br/>"
        "• <b>CSRF Token Defense:</b> Generates a 32-byte cryptographic token via <code>generateCsrfToken()</code> embedded into every form via <code>csrfField()</code>. Prevents cross-site attacks.<br/>"
        "• <b>XSS Protection & Sanitization:</b> The <code>e()</code> helper escapes user inputs using <code>htmlspecialchars()</code> to neutralize malicious script injections.<br/>"
        "• <b>Flash Notification System:</b> Implements one-time alert toasts (e.g., 'Application submitted successfully').",
        body_style
    ))
    story.append(Spacer(1, 6))

    story.append(Paragraph("B. Database Manager & Auto-Migration (config/db.php)", h2_style))
    story.append(Paragraph(
        "The <code>Database</code> class uses the <b>Singleton Pattern</b>. If the database <code>crms_db</code> does not exist, the system creates it on the fly and automatically imports <code>database/schema.sql</code> and <code>database/seed.sql</code>.",
        body_style
    ))
    story.append(Spacer(1, 6))

    # Example Code Snippet Box
    code_text = (
        "<b>// Example: PDO Singleton with Auto-Migration in config/db.php</b><br/>"
        "$dsn = \"mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4\";<br/>"
        "$options = [<br/>"
        "&nbsp;&nbsp;PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,<br/>"
        "&nbsp;&nbsp;PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC<br/>"
        "];<br/>"
        "$pdo = new PDO($dsn, $username, $password, $options);"
    )
    t_code = Table([[Paragraph(code_text, code_style)]], colWidths=[500])
    t_code.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor('#f1f5f9')),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor('#cbd5e1')),
        ('TOPPADDING', (0,0), (-1,-1), 6),
        ('BOTTOMPADDING', (0,0), (-1,-1), 6),
        ('LEFTPADDING', (0,0), (-1,-1), 10),
        ('RIGHTPADDING', (0,0), (-1,-1), 10),
    ]))
    story.append(t_code)
    story.append(Spacer(1, 14))

    # ------------------ SECTION 2 ------------------
    story.append(Paragraph("2. Authentication & Role-Based Authorization", h1_style))
    story.append(Paragraph(
        "CRMS enforces a strict separation between <b>Students</b> and <b>Placement Administrators</b>. Controlled by <code>AuthController.php</code> and <code>User.php</code>.",
        body_style
    ))
    story.append(Spacer(1, 6))

    auth_table_data = [
        [Paragraph("<b>User Role</b>", body_style), Paragraph("<b>Authentication Table</b>", body_style), Paragraph("<b>Landing Dashboard</b>", body_style), Paragraph("<b>Privileges & Capabilities</b>", body_style)],
        [
            Paragraph("<b>Super Admin</b>", body_style),
            Paragraph("<code>admins</code> (role='superadmin')", body_style),
            Paragraph("<code>?page=admin/dashboard</code>", body_style),
            Paragraph("Full control over students, companies, jobs, offers, and analytics reports.", body_style)
        ],
        [
            Paragraph("<b>Coordinator</b>", body_style),
            Paragraph("<code>admins</code> (role='coordinator')", body_style),
            Paragraph("<code>?page=admin/dashboard</code>", body_style),
            Paragraph("Manage job drives, shortlist applications, and update interview selections.", body_style)
        ],
        [
            Paragraph("<b>Student</b>", body_style),
            Paragraph("<code>students</code>", body_style),
            Paragraph("<code>?page=student/dashboard</code>", body_style),
            Paragraph("Manage academic profile, browse eligible placement drives, and track job offers.", body_style)
        ]
    ]
    t_auth = Table(auth_table_data, colWidths=[85, 120, 125, 170])
    t_auth.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.HexColor('#e2e8f0')),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor('#cbd5e1')),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor('#e2e8f0')),
        ('TOPPADDING', (0,0), (-1,-1), 5),
        ('BOTTOMPADDING', (0,0), (-1,-1), 5),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_auth)
    story.append(Spacer(1, 8))

    story.append(Paragraph(
        "<b>Password Security Example:</b> All passwords use standard bcrypt algorithm (<code>password_hash($pass, PASSWORD_BCRYPT)</code>). During login, <code>password_verify($inputPass, $row['password_hash'])</code> validates credentials without storing plaintext.",
        body_style
    ))
    story.append(Spacer(1, 14))

    # Page break for clean flow
    story.append(PageBreak())

    # ------------------ SECTION 3 ------------------
    story.append(Paragraph("3. Student Module & Automated Eligibility Engine", h1_style))
    story.append(Paragraph(
        "The student module allows eligible candidates to view placement opportunities, apply with a single click, and track interview progress.",
        body_style
    ))
    story.append(Spacer(1, 6))

    story.append(Paragraph("Automated Eligibility Engine (app/models/Job.php)", h2_style))
    story.append(Paragraph(
        "Before a student can click 'Apply', the backend dynamically validates four criteria:<br/>"
        "1. <b>CGPA Cutoff:</b> Verifies if <code>student.cgpa >= job.min_cgpa</code>.<br/>"
        "2. <b>Degree / Course Match:</b> Checks if student's course (e.g. <code>'B.Tech CSE'</code>) is inside <code>job.allowed_courses</code>.<br/>"
        "3. <b>Current Semester:</b> Checks if student's semester is listed in <code>job.allowed_semesters</code> (e.g. <code>'7,8'</code>).<br/>"
        "4. <b>Skill Match Percentage:</b> Calculates overlap between student skills and job requirements (e.g. Python, SQL).",
        body_style
    ))
    story.append(Spacer(1, 6))

    # Example Walkthrough Box
    ex_box = (
        "<b>Concrete Example:</b><br/>"
        "• <b>Job Drive:</b> Google Software Engineer | Min CGPA: <b>7.50</b> | Allowed Courses: <b>B.Tech CSE, B.Tech IT</b><br/>"
        "• <b>Candidate A (Rahul):</b> CGPA: <b>8.45</b> | Course: <b>B.Tech CSE</b> &rarr; <font color='#059669'><b>ELIGIBLE</b> (Can Click 'Apply')</font><br/>"
        "• <b>Candidate B (Priya):</b> CGPA: <b>7.10</b> | Course: <b>B.Tech CSE</b> &rarr; <font color='#dc2626'><b>INELIGIBLE</b> ('CGPA cutoff of 7.50 not satisfied')</font><br/>"
        "• <b>Candidate C (Amit):</b> CGPA: <b>8.90</b> | Course: <b>MCA</b> &rarr; <font color='#dc2626'><b>INELIGIBLE</b> ('Course MCA not listed in allowed degrees')</font>"
    )
    t_ex = Table([[Paragraph(ex_box, callout_style)]], colWidths=[500])
    t_ex.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor('#f0fdf4')),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor('#86efac')),
        ('TOPPADDING', (0,0), (-1,-1), 6),
        ('BOTTOMPADDING', (0,0), (-1,-1), 6),
        ('LEFTPADDING', (0,0), (-1,-1), 10),
        ('RIGHTPADDING', (0,0), (-1,-1), 10),
    ]))
    story.append(t_ex)
    story.append(Spacer(1, 14))

    # ------------------ SECTION 4 ------------------
    story.append(Paragraph("4. Placement Administrator Module & Screening Pipeline", h1_style))
    story.append(Paragraph(
        "Managed through <code>AdminController.php</code>. Provides full end-to-end recruitment management:",
        body_style
    ))
    story.append(Spacer(1, 6))

    story.append(Paragraph(
        "• <b>Company Directory:</b> Add, edit, and maintain partner recruiting companies, industry type, and contact person.<br/>"
        "• <b>Job Openings & Placement Drives:</b> Schedule drives, CTC packages, eligibility cutoffs, application deadlines, and drive status (Open / Closed).<br/>"
        "• <b>Application Screening Pipeline:</b> Filter candidates by CGPA, skills, or status. Step-by-step state progression:<br/>"
        "&nbsp;&nbsp;&nbsp;&nbsp;<code>Applied &rarr; Under Review &rarr; Shortlisted &rarr; Selected / Rejected</code><br/>"
        "• <b>Offer Management:</b> When candidate is marked <b>'Selected'</b>, the system records an official <b>Offer Reference ID</b> (e.g. <code>GOOG-2026-OFF-019</code>) in the <code>selections</code> table.<br/>"
        "• <b>Reports & Analytics:</b> Aggregated placement statistics, total offers, average salary package, and department-wise charts.",
        body_style
    ))
    story.append(Spacer(1, 14))

    # ------------------ SECTION 5 ------------------
    story.append(Paragraph("5. Database Relational Design (crms_db)", h1_style))
    story.append(Paragraph(
        "The relational database schema is normalized and enforced with Foreign Keys and Cascading Rules:",
        body_style
    ))
    story.append(Spacer(1, 6))

    schema_data = [
        [Paragraph("<b>Table</b>", body_style), Paragraph("<b>Primary Key</b>", body_style), Paragraph("<b>Foreign Keys</b>", body_style), Paragraph("<b>Description & Purpose</b>", body_style)],
        [
            Paragraph("<code>admins</code>", body_style),
            Paragraph("<code>admin_id</code>", body_style),
            Paragraph("None", body_style),
            Paragraph("System administrators and placement coordinators.", body_style)
        ],
        [
            Paragraph("<code>students</code>", body_style),
            Paragraph("<code>student_id</code>", body_style),
            Paragraph("None", body_style),
            Paragraph("Registered students, academic profile (CGPA, Course, Skills, Resume).", body_style)
        ],
        [
            Paragraph("<code>companies</code>", body_style),
            Paragraph("<code>company_id</code>", body_style),
            Paragraph("None", body_style),
            Paragraph("Partner recruiting corporate entities and contacts.", body_style)
        ],
        [
            Paragraph("<code>jobs</code>", body_style),
            Paragraph("<code>job_id</code>", body_style),
            Paragraph("<code>company_id</code> &rarr; companies", body_style),
            Paragraph("Placement drive postings, salary package, min CGPA, deadlines.", body_style)
        ],
        [
            Paragraph("<code>applications</code>", body_style),
            Paragraph("<code>application_id</code>", body_style),
            Paragraph("<code>job_id</code>, <code>student_id</code>", body_style),
            Paragraph("Tracks student applications. UNIQUE constraint on (job_id, student_id).", body_style)
        ],
        [
            Paragraph("<code>selections</code>", body_style),
            Paragraph("<code>selection_id</code>", body_style),
            Paragraph("<code>application_id</code> &rarr; applications", body_style),
            Paragraph("Final interview outcome, official offer letter reference & remarks.", body_style)
        ]
    ]
    t_schema = Table(schema_data, colWidths=[75, 80, 145, 200])
    t_schema.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.HexColor('#e2e8f0')),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor('#cbd5e1')),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor('#e2e8f0')),
        ('TOPPADDING', (0,0), (-1,-1), 5),
        ('BOTTOMPADDING', (0,0), (-1,-1), 5),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_schema)
    story.append(Spacer(1, 14))

    # Page break for clean flow
    story.append(PageBreak())

    # ------------------ SECTION 6 ------------------
    story.append(Paragraph("6. Quick Start, Testing & Seeded Credentials", h1_style))
    story.append(Paragraph(
        "Use these verified credentials to test each portal of the application locally:",
        body_style
    ))
    story.append(Spacer(1, 8))

    cred_data = [
        [Paragraph("<b>Portal</b>", body_style), Paragraph("<b>Email Address</b>", body_style), Paragraph("<b>Password</b>", body_style), Paragraph("<b>Role / Permissions</b>", body_style)],
        [
            Paragraph("<b>Admin Portal</b>", body_style),
            Paragraph("<code>admin@crms.edu</code>", body_style),
            Paragraph("<code>Admin@123</code>", body_style),
            Paragraph("Super Admin (Full Placement & Student Access)", body_style)
        ],
        [
            Paragraph("<b>Coordinator Portal</b>", body_style),
            Paragraph("<code>coordinator@crms.edu</code>", body_style),
            Paragraph("<code>Coord@123</code>", body_style),
            Paragraph("Placement Officer (Drive & Application Screening)", body_style)
        ],
        [
            Paragraph("<b>Student Portal</b>", body_style),
            Paragraph("<code>rahul.sharma@crms.edu</code>", body_style),
            Paragraph("<code>Student@123</code>", body_style),
            Paragraph("Candidate (B.Tech CSE, CGPA: 8.45)", body_style)
        ],
        [
            Paragraph("<b>Student Portal</b>", body_style),
            Paragraph("<code>priya.patel@crms.edu</code>", body_style),
            Paragraph("<code>Student@123</code>", body_style),
            Paragraph("Candidate (B.Tech IT, CGPA: 7.90)", body_style)
        ]
    ]
    t_cred = Table(cred_data, colWidths=[100, 150, 100, 150])
    t_cred.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,0), colors.HexColor('#e2e8f0')),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor('#cbd5e1')),
        ('INNERGRID', (0,0), (-1,-1), 0.5, colors.HexColor('#e2e8f0')),
        ('TOPPADDING', (0,0), (-1,-1), 5),
        ('BOTTOMPADDING', (0,0), (-1,-1), 5),
        ('LEFTPADDING', (0,0), (-1,-1), 6),
        ('RIGHTPADDING', (0,0), (-1,-1), 6),
    ]))
    story.append(t_cred)
    story.append(Spacer(1, 14))

    story.append(Paragraph("Execution Endpoints:", h2_style))
    story.append(Paragraph(
        "• <b>Application Web Portal:</b> <font color='#2563eb'><u>http://localhost/crms/</u></font><br/>"
        "• <b>phpMyAdmin Database Explorer:</b> <font color='#2563eb'><u>http://localhost/phpmyadmin/</u></font><br/>"
        "• <b>Direct Login URL:</b> <font color='#2563eb'><u>http://localhost/crms/public/index.php?page=login</u></font>",
        body_style
    ))
    story.append(Spacer(1, 20))

    # Sign-off box
    sign_off = (
        "<b>Document Summary:</b> This document serves as the complete academic technical specification for CRMS. "
        "It covers the software architecture, database entities, security models, eligibility validation engine, "
        "and administrative workflows."
    )
    t_sign = Table([[Paragraph(sign_off, callout_style)]], colWidths=[500])
    t_sign.setStyle(TableStyle([
        ('BACKGROUND', (0,0), (-1,-1), colors.HexColor('#f8fafc')),
        ('BOX', (0,0), (-1,-1), 1, colors.HexColor('#cbd5e1')),
        ('TOPPADDING', (0,0), (-1,-1), 8),
        ('BOTTOMPADDING', (0,0), (-1,-1), 8),
        ('LEFTPADDING', (0,0), (-1,-1), 12),
        ('RIGHTPADDING', (0,0), (-1,-1), 12),
    ]))
    story.append(t_sign)

    doc.build(story, canvasmaker=NumberedCanvas)
    print(f"PDF successfully generated at: {filename}")

if __name__ == '__main__':
    target = os.path.join(os.path.dirname(__file__), "CRMS_Complete_System_Guide.pdf")
    build_pdf(target)
