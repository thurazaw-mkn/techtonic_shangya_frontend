-- Drop tables in reverse dependency order to avoid foreign key conflicts
DROP TABLE IF EXISTS eoi;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS companies;

-- Create 'company' table
CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    industry VARCHAR(255),
    description TEXT,
    head_office_address VARCHAR(255),
    is_deleted BOOLEAN,
    created_at DATETIME,
    created_by VARCHAR(255),
    updated_at DATETIME,
    updated_by VARCHAR(255)
);

-- Create 'admins' table
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    display_name VARCHAR(255),
    password VARCHAR(255),
    role VARCHAR(255),
    email VARCHAR(255),
    company_id INT, 
    status INT,
    is_deleted BOOLEAN,
    created_at DATETIME,
    created_by VARCHAR(255),
    updated_at DATETIME,
    updated_by VARCHAR(255)
);

-- Create 'jobs' table
CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT,
    job_ref_number VARCHAR(255) UNIQUE,
    title VARCHAR(255),
    position VARCHAR(255),
    location VARCHAR(255),
    requirement_essential TEXT,
    requirement_preferable TEXT,
    salary_range VARCHAR(255),
    description TEXT,
    is_deleted BOOLEAN,
    created_at DATETIME,
    created_by VARCHAR(255),
    updated_at DATETIME,
    updated_by VARCHAR(255)
);

-- Create 'eoi' table
CREATE TABLE eoi (
    eoi_number INT AUTO_INCREMENT PRIMARY KEY,
    job_ref_number VARCHAR(255),
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    address_street VARCHAR(255),
    address_town VARCHAR(255),
    address_state VARCHAR(255),
    address_postcode VARCHAR(50),
    email VARCHAR(255),
    phone VARCHAR(50),
    skills TEXT,
    other_skills TEXT,
    status VARCHAR(50),
    is_deleted BOOLEAN,
    created_at DATETIME,
    created_by VARCHAR(255),
    updated_at DATETIME,
    updated_by VARCHAR(255)
);
