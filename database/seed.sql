USE evoting;

INSERT INTO roles (name) VALUES ('admin'), ('officer'), ('voter');

-- Development password for all sample accounts: ChangeMe123!
INSERT INTO users (role_id, name, email, password_hash) VALUES
(1, 'System Administrator', 'admin@evoting.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC8HnWwzY7Wj0nG3m2y'),
(2, 'Election Officer', 'officer@evoting.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC8HnWwzY7Wj0nG3m2y'),
(3, 'Demo Voter', 'voter@evoting.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC8HnWwzY7Wj0nG3m2y');

INSERT INTO voters (user_id, voter_code, verification_status) VALUES
(3, 'DEMO-VOTER-001', 'verified');

INSERT INTO positions (name, description) VALUES
('President', 'President of the organization'),
('Secretary', 'Secretary of the organization');

INSERT INTO parties (name, abbreviation, description) VALUES
('Independent', 'IND', 'Independent candidates');

INSERT INTO elections (title, description, election_type, starts_at, ends_at, status, created_by)
VALUES ('Demo Student Council Election', 'Development demonstration election.', 'Student Council', DATE_ADD(NOW(), INTERVAL 1 DAY), DATE_ADD(NOW(), INTERVAL 3 DAY), 'scheduled', 1);

INSERT INTO election_positions (election_id, position_id, max_choices)
SELECT 1, id, 1 FROM positions;

INSERT INTO candidates (first_name, last_name, biography, party_id)
VALUES ('Amine', 'Candidate', 'Development candidate A.', 1), ('Sara', 'Candidate', 'Development candidate B.', 1);

INSERT INTO election_candidates (election_id, position_id, candidate_id)
VALUES (1, 1, 1), (1, 1, 2);

INSERT INTO voter_eligibility (election_id, voter_id)
VALUES (1, 1);
