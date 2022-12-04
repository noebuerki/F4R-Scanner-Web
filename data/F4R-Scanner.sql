CREATE DATABASE `f4r-scanner.noebuerki-services.ch`;
USE `f4r-scanner.noebuerki-services.ch`;

CREATE TABLE `user` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `apiKey` VARCHAR(20) NOT NULL UNIQUE,
    `admin` BOOLEAN DEFAULT false
);

CREATE TABLE `section`(
    `id`INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `number` INT NOT NULL,
    `targetNumber` INT NOT NULL,
    `scanner`INT NOT NULL,
    `userId` INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES user(id)
);

CREATE TABLE `item`(
    `id` INT NOT NULL,
    `position` INT NOT NULL,
    `sectionId` INT NOT NULL,
    `barcode` VARCHAR(13) NOT NULL,
    `userId` INT NOT NULL,
    FOREIGN KEY (sectionId) REFERENCES section(id),
    FOREIGN KEY (userId) REFERENCES user(id)
)