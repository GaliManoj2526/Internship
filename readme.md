Task 2: Basic CRUD Blog Application
This project is the deliverable for Task 2 of the ApexPlanet Web Development Internship. It is a simple, functional blog application built with PHP and MySQL that demonstrates core web development concepts.

Features
User Authentication: Users can register for a new account and log in to a secure session.

Password Security: User passwords are securely hashed using modern PHP standards.

CRUD Operations for Posts: Logged-in users can Create, Read, Update, and Delete their own blog posts.

Ownership: Users can only view, edit, and delete the posts that they have created.

How to Set Up and Run This Project
To run this application on your local machine, please follow these steps:

Prerequisites
A local server environment like XAMPP, WAMP, or MAMP installed.

Apache and MySQL services must be running.

Installation
Clone or Download: Place all the project files into a new folder named blog inside your server's root directory (e.g., xampp/htdocs/blog).

Database Setup:

Open phpMyAdmin by navigating to http://localhost/phpmyadmin.

Create a new database named blog.

Select the blog database, go to the SQL tab, and execute the commands from the database.sql file included in this project. This will create the users and posts tables.

Run the Application:

Open your web browser and navigate to the registration page to start:

http://localhost/blog/register.php

Create a new user account, and then log in to access the blog dashboard.

Technologies Used
Backend: PHP

Database: MySQL

Frontend: HTML & CSS

Server: Apache (via XAMPP)