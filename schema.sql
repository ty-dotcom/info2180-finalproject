DROP DATABASE IF EXISTS dolphin_crm;
CREATE DATABASE dolphin_crm;
USE dolphin_crm;

DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
  `id` int(11) PRIMARY KEY NOT NULL auto_increment,
  `firstname` varchar(100) NOT NULL default '',
  `lastname` varchar(100) NOT NULL default '',
  `password` varchar(225) NOT NULL default '',
  `email` varchar(225) NOT NULL default '',
  `Role` varchar(50) NOT NULL default '',
  `created_at` datetime NOT NULL default current_timestamp
);

INSERT INTO `Users` (id, password, email, Role)
VALUES (1, SHA2('password123', 256), 'admin@project2.com', 'Admin');


DROP TABLE IF EXISTS `Contacts`;
CREATE TABLE `Contacts` (
  `id` int(11) PRIMARY KEY NOT NULL auto_increment,
  `title` varchar(50) NOT NULL default '',
  `firstname` varchar(100) NOT NULL default '',
  `lastname` varchar(100) NOT NULL default '',
  `email` varchar(225) NOT NULL default '',
  `telephone` varchar(100) NOT NULL default '',
  `company` varchar(225) NOT NULL default '',
  `type` varchar(100) NOT NULL default '',
  `assigned_to` int(11) NOT NULL default 0,
  `created_by` int(11) NOT NULL default 0,
  `created_at` datetime NOT NULL default current_timestamp,
  `updated_at` datetime NOT NULL default current_timestamp
);

DROP TABLE IF EXISTS `Notes`;
CREATE TABLE `Notes` (
  `id` int(11) PRIMARY KEY NOT NULL auto_increment,
  `contact_id` int(11) NOT NULL default 0,
  `comment` text NOT NULL default '',
  `created_by` int(11) NOT NULL default 0,
  `created_at` datetime NOT NULL default current_timestamp
);







