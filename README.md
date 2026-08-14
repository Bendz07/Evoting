# E-Voting System

Secure, modular electronic voting application built with PHP 8+, MySQL 8+, Bootstrap 5 and JavaScript.

## Architecture

The application follows a lightweight MVC architecture with services and middleware. Security uses PDO prepared statements, password hashing, CSRF protection, role-based authorization, secure sessions and audit logging.

## Project status

This repository is being implemented in ordered phases:

1. Project architecture and configuration
2. Database schema and seed data
3. Core framework and routing
4. Authentication and RBAC
5. Election, position and candidate management
6. Voter eligibility management
7. Secure voting workflow
8. Results and reporting
9. Administration, audit and monitoring
10. UI/UX, testing and documentation

## Requirements

- PHP 8.1+
- MySQL 8.0+
- Apache with mod_rewrite or compatible web server
- Composer 2+

## Installation

1. Copy `.env.example` to `.env`.
2. Create a MySQL database.
3. Import `database/schema.sql`, then `database/seed.sql`.
4. Run `composer install`.
5. Configure the web server document root to `public/`.
6. Set the database and application values in `.env`.

Development seed credentials are documented in `database/seed.sql`. Change all passwords before any non-development deployment.

## Security note

This project is intended as an academic/professional software project. A real public election requires independently audited cryptographic protocols, legal compliance, operational controls, accessibility, threat modeling, independent verification, and election-specific certification.