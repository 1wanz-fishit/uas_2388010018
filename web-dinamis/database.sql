CREATE DATABASE IF NOT EXISTS esport_db;
USE esport_db;

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  `photo_path` varchar(255) DEFAULT NULL,
  `game_info` varchar(255) DEFAULT NULL,
  `achievements` text DEFAULT NULL,
  `personal_data` text DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `tournaments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `date` date DEFAULT NULL,
  `details` text,
  PRIMARY KEY (`id`)
);

-- Default admin account (password is 'admin123')
INSERT INTO `admin_users` (`username`, `password`) VALUES
('admin', 'admin123');
