CREATE DATABASE IF NOT EXISTS evoting CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE evoting;

CREATE TABLE roles (
    id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id TINYINT UNSIGNED NOT NULL,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    status ENUM('active','suspended','blocked') NOT NULL DEFAULT 'active',
    last_login_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB;

CREATE TABLE voters (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL UNIQUE,
    voter_code VARCHAR(64) NOT NULL UNIQUE,
    phone VARCHAR(30) NULL,
    date_of_birth DATE NULL,
    verification_status ENUM('pending','verified','rejected') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_voters_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE parties (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    abbreviation VARCHAR(30) NULL,
    description TEXT NULL,
    logo_path VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE elections (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT NULL,
    election_type VARCHAR(80) NOT NULL,
    starts_at DATETIME NOT NULL,
    ends_at DATETIME NOT NULL,
    status ENUM('draft','scheduled','active','closed','archived') NOT NULL DEFAULT 'draft',
    results_public TINYINT(1) NOT NULL DEFAULT 1,
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_elections_creator FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_elections_status_dates (status, starts_at, ends_at)
) ENGINE=InnoDB;

CREATE TABLE positions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE election_positions (
    election_id BIGINT UNSIGNED NOT NULL,
    position_id BIGINT UNSIGNED NOT NULL,
    max_choices TINYINT UNSIGNED NOT NULL DEFAULT 1,
    PRIMARY KEY (election_id, position_id),
    CONSTRAINT fk_ep_election FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
    CONSTRAINT fk_ep_position FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE candidates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    biography TEXT NULL,
    photo_path VARCHAR(255) NULL,
    party_id BIGINT UNSIGNED NULL,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_candidates_party FOREIGN KEY (party_id) REFERENCES parties(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE election_candidates (
    election_id BIGINT UNSIGNED NOT NULL,
    position_id BIGINT UNSIGNED NOT NULL,
    candidate_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (election_id, position_id, candidate_id),
    CONSTRAINT fk_ec_election FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
    CONSTRAINT fk_ec_position FOREIGN KEY (position_id) REFERENCES positions(id) ON DELETE CASCADE,
    CONSTRAINT fk_ec_candidate FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE voter_eligibility (
    election_id BIGINT UNSIGNED NOT NULL,
    voter_id BIGINT UNSIGNED NOT NULL,
    status ENUM('eligible','ineligible','voted') NOT NULL DEFAULT 'eligible',
    eligible_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    voted_at DATETIME NULL,
    PRIMARY KEY (election_id, voter_id),
    CONSTRAINT fk_ve_election FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
    CONSTRAINT fk_ve_voter FOREIGN KEY (voter_id) REFERENCES voters(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE votes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    election_id BIGINT UNSIGNED NOT NULL,
    ballot_token_hash CHAR(64) NOT NULL UNIQUE,
    receipt_hash CHAR(64) NOT NULL UNIQUE,
    cast_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_votes_election FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,
    INDEX idx_votes_election (election_id)
) ENGINE=InnoDB;

CREATE TABLE vote_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vote_id BIGINT UNSIGNED NOT NULL,
    position_id BIGINT UNSIGNED NOT NULL,
    candidate_id BIGINT UNSIGNED NOT NULL,
    CONSTRAINT fk_vi_vote FOREIGN KEY (vote_id) REFERENCES votes(id) ON DELETE CASCADE,
    CONSTRAINT fk_vi_position FOREIGN KEY (position_id) REFERENCES positions(id),
    CONSTRAINT fk_vi_candidate FOREIGN KEY (candidate_id) REFERENCES candidates(id),
    UNIQUE KEY uq_vote_position (vote_id, position_id)
) ENGINE=InnoDB;

CREATE TABLE audit_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    description VARCHAR(500) NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(512) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_audit_created (created_at),
    INDEX idx_audit_action (action)
) ENGINE=InnoDB;

CREATE TABLE login_attempts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(190) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    successful TINYINT(1) NOT NULL DEFAULT 0,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_login_email_time (email, attempted_at),
    INDEX idx_login_ip_time (ip_address, attempted_at)
) ENGINE=InnoDB;

CREATE TABLE notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    read_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE system_settings (
    setting_key VARCHAR(100) PRIMARY KEY,
    setting_value TEXT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;
