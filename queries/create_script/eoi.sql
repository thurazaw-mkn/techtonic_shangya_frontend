DROP TABLE IF EXISTS eoi;

CREATE TABLE eoi (
    eoi_number INT AUTO_INCREMENT PRIMARY KEY,
    job_ref_number VARCHAR(255),
    first_name VARCHAR(255),
    last_name VARCHAR(255),
    address_street VARCHAR(255),
    address_town VARCHAR(255),
    address_state VARCHAR(255),
    address_postcode VARCHAR(20),
    email VARCHAR(255),
    phone VARCHAR(50),
    skills TEXT,
    other_skills TEXT,
    status VARCHAR(100),
    created_at DATETIME,
    created_by VARCHAR(255),
    updated_at DATETIME,
    updated_by VARCHAR(255),
    FOREIGN KEY (job_ref_number) REFERENCES jobs(job_ref_number)
);
