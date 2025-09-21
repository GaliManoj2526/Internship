-- File: database.sql
-- This script sets up the database structure for the Task-2 blog application.

-- Step 1: Create the Database (if it doesn't already exist)
-- This command ensures you have a database named 'blog' to work with.
CREATE DATABASE IF NOT EXISTS blog;

-- Step 2: Select the Database
-- This command tells MySQL to use the 'blog' database for all subsequent commands.
USE blog;

-- Step 3: Create the 'users' Table
-- This table will store user login information.
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Step 4: Create the 'posts' Table
-- This table will store the blog posts created by users.
CREATE TABLE `posts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `content` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;