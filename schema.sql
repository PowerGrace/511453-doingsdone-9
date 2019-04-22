CREATE DATABASE doingsdone
 DEFAULT CHARACTER SET utf8
 DEFAULT COLLATE utf8_general_ci;
USE doingsdone;
CREATE TABLE projects (
project CHAR(64) NOT NULL UNIQUE);
CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
email CHAR(128) NOT NULL UNIQUE,
password CHAR(64) NOT NULL UNIQUE,
date_reg DATETIME);
CREATE TABLE tasks(
dt_creat DATETIME,
status BOOL,
name TEXT NOT NULL,
file CHAR(128) UNIQUE,
deadline TEXT);
