CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('applicant','professional','reviewer','building_official','inspector','admin') NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE professional_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    profession ENUM('architect','civil_structural','electrical','mechanical','pece','plumbing_sanitary','other') NOT NULL,
    license_no VARCHAR(100) NULL,
    verification_status ENUM('pending','verified','rejected') NOT NULL DEFAULT 'pending',
    verified_by INT NULL,
    verified_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_prof_user
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_prof_verified_by
        FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE disciplines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(60) NOT NULL UNIQUE,
    name VARCHAR(120) NOT NULL,
    sort_order INT NOT NULL DEFAULT 100,
    is_active TINYINT(1) NOT NULL DEFAULT 1
);

INSERT INTO disciplines (code,name,sort_order) VALUES
('architectural','Architectural',10),
('civil_structural','Civil / Structural',20),
('electrical','Electrical',30),
('mechanical','Mechanical',40),
('electronics','Electronics',50),
('plumbing_sanitary','Plumbing / Sanitary',60),
('other','Other',100);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_user_id INT NOT NULL,
    project_name VARCHAR(200) NOT NULL,
    project_location VARCHAR(255) NOT NULL,
    project_type VARCHAR(150) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_project_owner
        FOREIGN KEY (owner_user_id) REFERENCES users(id)
);

CREATE TABLE permit_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    application_no VARCHAR(60) NOT NULL UNIQUE,
    status ENUM(
        'draft',
        'submitted',
        'under_review',
        'correction_required',
        'approved',
        'permit_issued',
        'rejected'
    ) NOT NULL DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_application_project
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);

CREATE TABLE application_disciplines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    discipline_id INT NOT NULL,
    required_flag TINYINT(1) NOT NULL DEFAULT 1,
    assigned_reviewer_id INT NULL,
    status ENUM(
        'not_submitted',
        'submitted',
        'under_review',
        'correction_required',
        'resubmitted',
        'approved',
        'rejected'
    ) NOT NULL DEFAULT 'not_submitted',
    latest_version_id INT NULL,
    approved_version_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_application_discipline (application_id,discipline_id),
    CONSTRAINT fk_ad_application
        FOREIGN KEY (application_id) REFERENCES permit_applications(id) ON DELETE CASCADE,
    CONSTRAINT fk_ad_discipline
        FOREIGN KEY (discipline_id) REFERENCES disciplines(id),
    CONSTRAINT fk_ad_reviewer
        FOREIGN KEY (assigned_reviewer_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE application_professionals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    application_discipline_id INT NOT NULL UNIQUE,
    professional_user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_ap_application
        FOREIGN KEY (application_id) REFERENCES permit_applications(id) ON DELETE CASCADE,
    CONSTRAINT fk_ap_application_discipline
        FOREIGN KEY (application_discipline_id) REFERENCES application_disciplines(id) ON DELETE CASCADE,
    CONSTRAINT fk_ap_professional
        FOREIGN KEY (professional_user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    application_discipline_id INT NOT NULL,
    title VARCHAR(180) NOT NULL,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_document_title (application_id,application_discipline_id,title),
    CONSTRAINT fk_document_application
        FOREIGN KEY (application_id) REFERENCES permit_applications(id) ON DELETE CASCADE,
    CONSTRAINT fk_document_application_discipline
        FOREIGN KEY (application_discipline_id) REFERENCES application_disciplines(id) ON DELETE CASCADE,
    CONSTRAINT fk_document_creator
        FOREIGN KEY (created_by) REFERENCES users(id)
);

CREATE TABLE document_versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    document_id INT NOT NULL,
    version_number INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_by INT NOT NULL,
    status ENUM(
        'submitted',
        'under_review',
        'correction_required',
        'resubmitted',
        'approved',
        'rejected'
    ) NOT NULL DEFAULT 'submitted',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_document_version (document_id,version_number),
    CONSTRAINT fk_dv_document
        FOREIGN KEY (document_id) REFERENCES documents(id) ON DELETE CASCADE,
    CONSTRAINT fk_dv_uploader
        FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

ALTER TABLE application_disciplines
    ADD CONSTRAINT fk_ad_latest_version
        FOREIGN KEY (latest_version_id) REFERENCES document_versions(id) ON DELETE SET NULL,
    ADD CONSTRAINT fk_ad_approved_version
        FOREIGN KEY (approved_version_id) REFERENCES document_versions(id) ON DELETE SET NULL;

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL,
    application_discipline_id INT NOT NULL,
    document_version_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    decision ENUM('approved','correction_required','rejected') NOT NULL,
    comments TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_review_application
        FOREIGN KEY (application_id) REFERENCES permit_applications(id) ON DELETE CASCADE,
    CONSTRAINT fk_review_application_discipline
        FOREIGN KEY (application_discipline_id) REFERENCES application_disciplines(id) ON DELETE CASCADE,
    CONSTRAINT fk_review_document_version
        FOREIGN KEY (document_version_id) REFERENCES document_versions(id) ON DELETE CASCADE,
    CONSTRAINT fk_review_reviewer
        FOREIGN KEY (reviewer_id) REFERENCES users(id)
);

CREATE TABLE correction_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    review_id INT NOT NULL,
    application_id INT NOT NULL,
    application_discipline_id INT NOT NULL,
    document_version_id INT NOT NULL,
    requested_by INT NOT NULL,
    comments TEXT NOT NULL,
    status ENUM('open','resolved','closed') NOT NULL DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at DATETIME NULL,
    CONSTRAINT fk_correction_review
        FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE,
    CONSTRAINT fk_correction_application
        FOREIGN KEY (application_id) REFERENCES permit_applications(id) ON DELETE CASCADE,
    CONSTRAINT fk_correction_application_discipline
        FOREIGN KEY (application_discipline_id) REFERENCES application_disciplines(id) ON DELETE CASCADE,
    CONSTRAINT fk_correction_version
        FOREIGN KEY (document_version_id) REFERENCES document_versions(id) ON DELETE CASCADE,
    CONSTRAINT fk_correction_requested_by
        FOREIGN KEY (requested_by) REFERENCES users(id)
);

CREATE TABLE permits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT NOT NULL UNIQUE,
    permit_no VARCHAR(70) NOT NULL UNIQUE,
    verification_token VARCHAR(120) NOT NULL UNIQUE,
    status ENUM('issued','suspended','revoked','expired') NOT NULL DEFAULT 'issued',
    issued_by INT NOT NULL,
    issued_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_permit_application
        FOREIGN KEY (application_id) REFERENCES permit_applications(id) ON DELETE CASCADE,
    CONSTRAINT fk_permit_issuer
        FOREIGN KEY (issued_by) REFERENCES users(id)
);

CREATE TABLE inspections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    permit_id INT NOT NULL,
    inspector_id INT NOT NULL,
    result ENUM('compliant','with_findings','follow_up_required') NOT NULL,
    findings TEXT NOT NULL,
    corrective_action TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_inspection_permit
        FOREIGN KEY (permit_id) REFERENCES permits(id) ON DELETE CASCADE,
    CONSTRAINT fk_inspection_inspector
        FOREIGN KEY (inspector_id) REFERENCES users(id)
);

CREATE TABLE inspection_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    inspection_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    caption VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_inspection_photo
        FOREIGN KEY (inspection_id) REFERENCES inspections(id) ON DELETE CASCADE
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    application_id INT NULL,
    title VARCHAR(160) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notification_user
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_notification_application
        FOREIGN KEY (application_id) REFERENCES permit_applications(id) ON DELETE CASCADE
);

CREATE TABLE audit_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    application_id INT NULL,
    action VARCHAR(255) NOT NULL,
    entity_type VARCHAR(80) NULL,
    entity_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_audit_application (application_id),
    INDEX idx_audit_user (user_id),
    CONSTRAINT fk_audit_user
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_audit_application
        FOREIGN KEY (application_id) REFERENCES permit_applications(id) ON DELETE SET NULL
);
