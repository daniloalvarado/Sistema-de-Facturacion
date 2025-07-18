-- phpMyAdmin SQL Dump
-- version 5.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS invoice_db;
USE invoice_db;

CREATE TABLE `category_list` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = product, 2 = service',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `category_list` VALUES
(6, 'Productos de Limpieza', '<p>Limpiar, lavar, aseo personal</p>', 1, '2025-03-11 11:22:58', NULL),
(7, 'Servicio de Delirery', '<p>Entrega de materiales</p>', 2, '2025-03-11 11:23:35', NULL);

CREATE TABLE `invoices_items` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(30) NOT NULL,
  `form_id` int(30) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `quantity` float NOT NULL,
  `price` float NOT NULL,
  `total` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `invoice_list` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `invoice_code` varchar(100) NOT NULL,
  `customer_name` text NOT NULL,
  `type` tinyint(1),
  `sub_total` float NOT NULL,
  `tax_rate` float NOT NULL,
  `total_amount` float NOT NULL,
  `remarks` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `product_list` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `category_id` int(30) NOT NULL,
  `product` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `product_list` VALUES
(16, 6, 'Ayudín', '<p>--</p>', 5, '2025-03-11 11:27:29', '2025-03-11 11:27:46');

CREATE TABLE `service_list` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `category_id` int(30) NOT NULL,
  `service` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `price` float NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `service_list` VALUES
(4, 7, 'De San Juan a Punchana', '', 20, '2025-03-11 11:40:46', '2025-03-11 11:42:53');

CREATE TABLE `system_info` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `meta_field` text NOT NULL,
  `meta_value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `system_info` VALUES
(1, 'name', 'Sistema de Facturación'),
(6, 'short_name', 'Facturas'),
(11, 'logo', 'uploads/1741397760_backbone.svg'),
(13, 'user_avatar', 'uploads/user_avatar.jpg'),
(14, 'cover', 'uploads/1624240440_banner1.jpg'),
(15, 'tax_rate', '12');

CREATE TABLE `users` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `date_added` datetime NOT NULL DEFAULT current_timestamp(),
  `date_updated` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` VALUES
(3, 'Danilo', 'Alvarado', 'danilo', '$2y$10$MHQWj579jSdDsltrf4iSlOHnqD1502Q2myTQV/i1YqRGbWxh8RvHu', 'uploads/1741397700_5.jpg', '2025-07-16 19:51:42', 1, '2025-03-07 12:28:27', '2025-07-16 19:51:42');

COMMIT;
