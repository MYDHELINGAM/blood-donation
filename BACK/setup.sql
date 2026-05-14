-- Create the database if it doesn't exist
CREATE DATABASE IF NOT EXISTS blood_donation_db;
USE blood_donation_db;

-- Create the donors table
CREATE TABLE IF NOT EXISTS donors (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT UNSIGNED NOT NULL,
    blood_group VARCHAR(3) NOT NULL,
    phone VARCHAR(20) NOT NULL
);