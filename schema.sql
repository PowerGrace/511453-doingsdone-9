CREATE DATABASE doingsdone
 DEFAULT CHARACTER SET utf8
 DEFAULT COLLATE utf8_general_ci;

USE doingsdone;

CREATE TABLE projects (
id_name INT AUTO_INCREMENT PRIMARY KEY, 
name CHAR(64) NOT NULL UNIQUE,
id_user INT NOT NULL
);

CREATE TABLE users (
id_user INT AUTO_INCREMENT PRIMARY KEY,
name_user CHAR(128) NOT NULL UNIQUE,
email CHAR(128) NOT NULL UNIQUE,
password CHAR(64) NOT NULL UNIQUE,
date_reg DATETIME NOT NULL
);

CREATE TABLE tasks(
id_task INT AUTO_INCREMENT PRIMARY KEY,
dt_creat DATETIME NOT NULL,
status BOOL DEFAULT FALSE,
name TEXT NOT NULL,
file CHAR(128) UNIQUE,
deadline DATETIME NULL,
id_name INT NOT NULL
);