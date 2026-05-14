-- Create the database
CREATE DATABASE IF NOT EXISTS blood_donation;
USE blood_donation;

-- Create the donors table
CREATE TABLE IF NOT EXISTS donors (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT(3) NOT NULL,
    blood_group VARCHAR(5) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_donor (name, age, blood_group, phone)
); 