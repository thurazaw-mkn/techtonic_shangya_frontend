DROP TABLE IF EXISTS admin;

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    password VARCHAR(255),
    display_name VARCHAR(255),
    role VARCHAR(100),
    email VARCHAR(255),
    created_at DATETIME,
    created_by VARCHAR(255),
    updated_at DATETIME,
    updated_by VARCHAR(255)
);
