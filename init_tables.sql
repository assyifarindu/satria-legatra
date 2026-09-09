USE satria_legatra;

SET FOREIGN_KEY_CHECKS = 0;

-- =========================================
-- DROP TABLES
-- =========================================

DROP TABLE IF EXISTS tsp_request_document_feedback_file_downloads;
DROP TABLE IF EXISTS tsp_request_document_feedback_files;
DROP TABLE IF EXISTS tsp_request_document_feedbacks;
DROP TABLE IF EXISTS tsp_request_document_file_downloads;
DROP TABLE IF EXISTS tsp_request_document_files;
DROP TABLE IF EXISTS tsp_request_document_customer_pics;
DROP TABLE IF EXISTS tsp_request_document_customers;
DROP TABLE IF EXISTS tsp_request_document_pics;
DROP TABLE IF EXISTS tsp_request_document_committees;
DROP TABLE IF EXISTS tsp_request_document_histories;
DROP TABLE IF EXISTS tsp_form_legal_reviews;
DROP TABLE IF EXISTS tsp_request_documents;
DROP TABLE IF EXISTS tsp_request_substages;
DROP TABLE IF EXISTS tsp_request_stages;
DROP TABLE IF EXISTS tsp_request_status;
DROP TABLE IF EXISTS tsp_audit_trails;


-- =========================================
-- MASTER: REQUEST STAGES
-- =========================================

CREATE TABLE tsp_request_stages (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    sequence INT NOT NULL,
    stage VARCHAR(100) NOT NULL,
    sla_hours INT NOT NULL,
    created_by BIGINT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_by BIGINT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL
);

INSERT INTO tsp_request_stages
(sequence, stage, sla_hours, created_by, created_at)
VALUES
(1, 'Request Document', 24, 1, NOW()),
(2, 'Legal Drafting (LD)', 120, 1, NOW()),
(3, 'Feedback LD', 48, 1, NOW()),
(4, 'Negotiation', 48, 1, NOW()),
(5, 'Form Legal Review (FLR)', 48, 1, NOW()),
(6, 'Feedback FLR', 24, 1, NOW()),
(7, 'Under Review BOD', 48, 1, NOW()),
(8, 'Cleared for Delivery', 24, 1, NOW()),
(9, 'Final Contract', 24, 1, NOW()),
(10, 'Document Filing', 24, 1, NOW()),
(11, 'Contract Active', 0, 1, NOW());


-- =========================================
-- MASTER: REQUEST SUBSTAGES
-- =========================================

CREATE TABLE tsp_request_substages (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    stage_id BIGINT NOT NULL,
    sequence INT NOT NULL,
    substage VARCHAR(100) NOT NULL,
    sla_hours INT NOT NULL,
    created_by BIGINT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_by BIGINT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_tsp_request_substages_stage
        FOREIGN KEY (stage_id)
        REFERENCES tsp_request_stages(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

INSERT INTO tsp_request_substages
(stage_id, sequence, substage, sla_hours, created_by, created_at)
VALUES
(3, 1, 'Under Review User', 48, 1, NOW()),
(3, 2, 'Under Review Committee', 48, 1, NOW()),
(3, 3, 'Revision', 48, 1, NOW()),
(6, 1, 'Under Review User', 48, 1, NOW()),
(6, 2, 'Under Review Committee', 48, 1, NOW()),
(6, 3, 'Revision', 48, 1, NOW());


-- =========================================
-- MASTER: REQUEST STATUS
-- =========================================

CREATE TABLE tsp_request_status (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    status VARCHAR(100) NOT NULL,
    description VARCHAR(255) NOT NULL,
    created_by BIGINT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_by BIGINT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL
);

INSERT INTO tsp_request_status
(id, status, description, created_by, created_at)
VALUES
(1, 'Draft', 'User create draft request document', 1, NOW()),
(2, 'Submitted', 'User submitted request document', 1, NOW()),
(3, 'Cancel', 'User cancelled request document', 1, NOW()),
(4, 'Decline', 'Request document declined', 1, NOW()),
(5, 'Drafting', 'Request document is being drafted by Admin', 1, NOW()),
(6, 'User Review', 'Request document is under user review', 1, NOW()),
(7, 'Verified By User', 'Request document verified by user', 1, NOW()),
(8, 'Committee Review', 'Request document is under committee review', 1, NOW()),
(9, 'Verified By Committee', 'Request document verified by committee', 1, NOW()),
(10, 'Need Revision', 'Legal Drafting needs revision by Admin', 1, NOW()),
(11, 'Need Revision', 'Request document needs revision by User', 1, NOW()),
(12, 'Need Revision', 'Form Legal Review needs revision by Admin', 1, NOW()),
(13, 'Final Check', 'Final Check', 1, NOW()),
(14, 'Fully Approved', 'Fully Approved', 1, NOW()),
(15, 'Cleared for Delivery', 'Cleared for Delivery', 1, NOW());


-- =========================================
-- TRANSACTION: REQUEST DOCUMENTS
-- =========================================

CREATE TABLE tsp_request_documents (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    stage_id BIGINT NOT NULL,
    substage_id BIGINT NULL,
    status_id BIGINT NOT NULL,

    document_number VARCHAR(100) NULL,
    title VARCHAR(255) NOT NULL,

    contract_type ENUM('Part', 'Service', 'Reman', 'Unit') NULL,

    requester_id BIGINT NOT NULL,

    potential_amount DECIMAL(20,2) NULL,

    sign_status ENUM(
        'Not Signed',
        'Partial Signed',
        'Fully Signed'
    ) NULL,

    is_project BOOLEAN NULL DEFAULT NULL,

    sow TEXT NULL,
    transaction_procedure TEXT NULL,
    kpi TEXT NULL,

    created_by BIGINT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_by BIGINT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_request_documents_stage
        FOREIGN KEY (stage_id)
        REFERENCES tsp_request_stages(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_request_documents_substage
        FOREIGN KEY (substage_id)
        REFERENCES tsp_request_substages(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL,

    CONSTRAINT fk_request_documents_status
        FOREIGN KEY (status_id)
        REFERENCES tsp_request_status(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


-- =========================================
-- REQUEST DOCUMENT PICS
-- =========================================

CREATE TABLE tsp_request_document_pics (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    request_document_id BIGINT NOT NULL,

    name VARCHAR(100) NULL,
    position VARCHAR(100) NULL,
    email VARCHAR(50) NULL,
    phone VARCHAR(20) NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_by BIGINT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_request_document_pics_document
        FOREIGN KEY (request_document_id)
        REFERENCES tsp_request_documents(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);


-- =========================================
-- REQUEST DOCUMENT CUSTOMERS
-- =========================================

CREATE TABLE tsp_request_document_customers (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    request_document_id BIGINT NOT NULL,

    name VARCHAR(100) NOT NULL,
    nib VARCHAR(100) NULL,
    npwp VARCHAR(100) NULL,
    address VARCHAR(255) NULL,
    postal_code VARCHAR(10) NULL,
    customer_group VARCHAR(100) NULL,
    email VARCHAR(50) NULL,

    created_by BIGINT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_by BIGINT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_request_document_customers_document
        FOREIGN KEY (request_document_id)
        REFERENCES tsp_request_documents(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);


-- =========================================
-- REQUEST DOCUMENT CUSTOMER PICS
-- =========================================

CREATE TABLE tsp_request_document_customer_pics (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    request_document_customer_id BIGINT NOT NULL,

    name VARCHAR(100) NULL,
    position VARCHAR(100) NULL,
    email VARCHAR(50) NULL,
    phone VARCHAR(20) NULL,

    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_by BIGINT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_request_document_customer_pics_customer
        FOREIGN KEY (request_document_customer_id)
        REFERENCES tsp_request_document_customers(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);


-- =========================================
-- REQUEST DOCUMENT FILES
-- =========================================

CREATE TABLE tsp_request_document_files (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    request_document_id BIGINT NOT NULL,

    name VARCHAR(255) NOT NULL,

    document_type ENUM(
        'Draft Contract',
        'Quotation',
        'Other',
        'Final Contract',
        'Signed BOD',
        'Signed Customer',
        'Contract Clauses'
    ) NOT NULL,

    file_path TEXT NOT NULL,

    created_by BIGINT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_by BIGINT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_request_document_files_document
        FOREIGN KEY (request_document_id)
        REFERENCES tsp_request_documents(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);


-- =========================================
-- REQUEST DOCUMENT FILE DOWNLOADS
-- =========================================

CREATE TABLE tsp_request_document_file_downloads (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    request_document_file_id BIGINT NOT NULL,
    download_by BIGINT NOT NULL,
    download_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_request_document_file_downloads_file
        FOREIGN KEY (request_document_file_id)
        REFERENCES tsp_request_document_files(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);


-- =========================================
-- REQUEST DOCUMENT COMMITTEES
-- =========================================

CREATE TABLE tsp_request_document_committees (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    request_document_id BIGINT NOT NULL,
    sequence INT NOT NULL,
    committee_id BIGINT NOT NULL,

    verification_ld_status BOOLEAN NOT NULL DEFAULT FALSE,
    verification_flr_status BOOLEAN NOT NULL DEFAULT FALSE,

    created_by BIGINT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_by BIGINT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,

    CONSTRAINT fk_request_document_committees_document
        FOREIGN KEY (request_document_id)
        REFERENCES tsp_request_documents(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);


-- =========================================
-- REQUEST DOCUMENT FEEDBACKS
-- =========================================

CREATE TABLE tsp_request_document_feedbacks (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    request_document_id BIGINT NOT NULL,
    stage_id BIGINT NOT NULL,
    substage_id BIGINT NULL,
    history_id BIGINT NULL,

    remark TEXT NOT NULL,

    CONSTRAINT fk_request_document_feedbacks_document
        FOREIGN KEY (request_document_id)
        REFERENCES tsp_request_documents(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_request_document_feedbacks_stage
        FOREIGN KEY (stage_id)
        REFERENCES tsp_request_stages(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_request_document_feedbacks_substage
        FOREIGN KEY (substage_id)
        REFERENCES tsp_request_substages(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_request_document_feedbacks_history
        FOREIGN KEY (history_id)
        REFERENCES tsp_request_document_histories(id)
        ON UPDATE CASCADE
        ON DELETE SET NULL
);


-- =========================================
-- REQUEST DOCUMENT FEEDBACK FILES
-- =========================================

CREATE TABLE tsp_request_document_feedback_files (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    request_document_feedback_id BIGINT NOT NULL,

    name VARCHAR(255) NOT NULL,
    file_path TEXT NOT NULL,

    CONSTRAINT fk_request_document_feedback_files_feedback
        FOREIGN KEY (request_document_feedback_id)
        REFERENCES tsp_request_document_feedbacks(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);


-- =========================================
-- REQUEST DOCUMENT FEEDBACK FILE DOWNLOADS
-- =========================================

CREATE TABLE tsp_request_document_feedback_file_downloads (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    request_document_feedback_file_id BIGINT NOT NULL,

    download_by BIGINT NOT NULL,
    download_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_request_document_feedback_files_download_feedback
        FOREIGN KEY (request_document_feedback_file_id)
        REFERENCES tsp_request_document_feedback_files(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);


-- =========================================
-- FORM LEGAL REVIEWS
-- =========================================

CREATE TABLE tsp_form_legal_reviews (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    request_document_id BIGINT NOT NULL,

    date DATE NOT NULL,
    department VARCHAR(255) NOT NULL,
    document_objective VARCHAR(255) NOT NULL,
    period_time VARCHAR(255) NOT NULL,
    incoterm VARCHAR(255) NOT NULL,
    work_location VARCHAR(255) NOT NULL,
    delivery_location VARCHAR(255) NOT NULL,
    term_of_payment VARCHAR(255) NOT NULL,

    resume TEXT NOT NULL,
    legal_note TEXT NOT NULL,

    CONSTRAINT fk_form_legal_reviews_document
        FOREIGN KEY (request_document_id)
        REFERENCES tsp_request_documents(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);


-- =========================================
-- REQUEST DOCUMENT HISTORIES
-- =========================================

CREATE TABLE tsp_request_document_histories (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    request_document_id BIGINT NOT NULL,
    stage_id BIGINT NOT NULL,
    substage_id BIGINT NULL,
    status_id BIGINT NOT NULL,

    action VARCHAR(100) NOT NULL,
    action_by BIGINT NOT NULL,
    assigned_to BIGINT NULL,

    created_by BIGINT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_request_document_histories_document
        FOREIGN KEY (request_document_id)
        REFERENCES tsp_request_documents(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,

    CONSTRAINT fk_request_document_histories_stage
        FOREIGN KEY (stage_id)
        REFERENCES tsp_request_stages(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_request_document_histories_substage
        FOREIGN KEY (substage_id)
        REFERENCES tsp_request_substages(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_request_document_histories_status
        FOREIGN KEY (status_id)
        REFERENCES tsp_request_status(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);


-- =========================================
-- AUDIT TRAILS
-- =========================================

CREATE TABLE tsp_audit_trails (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,

    entity_name VARCHAR(100) NOT NULL,
    entity_id BIGINT NOT NULL,

    old_value TEXT NULL,
    new_value TEXT NOT NULL
);


SET FOREIGN_KEY_CHECKS = 1;