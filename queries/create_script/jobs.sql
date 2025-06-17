-- Drop the existing table if it exists
DROP TABLE IF EXISTS jobs;

-- Create the updated jobs table
CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_ref_number VARCHAR(255) UNIQUE,
    title VARCHAR(255),
    position VARCHAR(255),
    location VARCHAR(255),
    requirement_essential TEXT,
    requirement_preferable TEXT,
    salary_range VARCHAR(100),
    description TEXT,
    created_at DATETIME,
    created_by VARCHAR(255),
    updated_at DATETIME,
    updated_by VARCHAR(255)
);
