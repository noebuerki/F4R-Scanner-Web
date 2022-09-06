CREATE DATABASE `futurascanner.noebuerki-services.ch`;

USE `futurascanner.noebuerki-services.ch`;

CREATE TABLE `user` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `admin` BOOLEAN DEFAULT false
);

CREATE TABLE `block` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `userId` INT NOT NULL,
    `blockNumber` INT NOT NULL,
    `itemQuantity` INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES `user` (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE `item` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `userId` INT NOT NULL,
    `blockId` INT NOT NULL,
    `position` INT NOT NULL,
    `barcode` VARCHAR(15) NOT NULL,
    FOREIGN KEY (userId) REFERENCES `user` (id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (blockId) REFERENCES `block` (id) ON UPDATE CASCADE ON DELETE CASCADE
);