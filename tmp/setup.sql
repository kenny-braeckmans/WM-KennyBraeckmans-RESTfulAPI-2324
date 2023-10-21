USE ID403618_testdb;

DROP TABLE IF EXISTS employee_project;

DROP TABLE IF EXISTS projects;

DROP TABLE IF EXISTS employees;

CREATE TABLE employees (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `first_name` VARCHAR(255) NOT NULL,
    `last_name` VARCHAR(255) NOT NULL,
    `specialization` VARCHAR(255) NOT NULL
);

CREATE TABLE projects (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) NOT NULL
);

CREATE TABLE employee_project (
    `employee_id` INT,
    `project_id` INT,
    PRIMARY KEY (`employee_id`, `project_id`),
    FOREIGN KEY (`employee_id`) REFERENCES employees(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`project_id`) REFERENCES projects(`id`) ON DELETE CASCADE
);

-- Inserting mock data into employees table
INSERT INTO employees (first_name, last_name, specialization) VALUES 
('John', 'Doe', 'Web Developer'),
('Jane', 'Smith', 'Database Administrator'),
('Robert', 'Brown', 'Software Engineer'),
('Emily', 'Johnson', 'UI/UX Designer'),
('Michael', 'White', 'Systems Analyst');

-- Inserting mock data into projects table
INSERT INTO projects (name, code, description) VALUES 
('Website Redesign', 'WEB123', 'Redesigning the company website for a modern look'),
('Inventory System', 'INV456', 'Building a new inventory management system'),
('Mobile App', 'APP789', 'Developing a mobile app for online shopping'),
('HR System', 'HR101', 'Implementing a new HR management system'),
('E-Commerce Portal', 'ECM202', 'Creating a new e-commerce platform');

-- Linking employees to projects
-- Assuming an employee can be associated with multiple projects and vice-versa
INSERT INTO employee_project (employee_id, project_id) VALUES 
(1, 1),  -- John Doe is working on Website Redesign
(1, 3),  -- John Doe is also working on Mobile App
(2, 2),  -- Jane Smith is working on Inventory System
(3, 2),  -- Robert Brown is working on Inventory System
(3, 3),  -- Robert Brown is also working on Mobile App
(4, 1),  -- Emily Johnson is working on Website Redesign
(5, 4);  -- Michael White is working on HR System
