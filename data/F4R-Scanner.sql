CREATE DATABASE `f4r-scanner.noebuerki-services.ch`;
USE `f4r-scanner.noebuerki-services.ch`;

CREATE TABLE `user` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `email` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `apiKey` VARCHAR(25) NOT NULL UNIQUE,
    `admin` BOOLEAN DEFAULT false
);

CREATE TABLE `stocktaking` (
    `id`INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `date` VARCHAR(10) NOT NULL,
    `time` VARCHAR(9) NOT NULL,
    `userId` INT NOT NULL,
    FOREIGN KEY (userId) REFERENCES user(id)
    ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE `section`(
    `id`INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `number` INT NOT NULL,
    `targetQuantity` INT NOT NULL,
    `branch`INT NOT NULL,
    `deviceNumber`INT NOT NULL,
    `stocktakingId` INT NOT NULL,
    `userId` INT NOT NULL,
    FOREIGN KEY (stocktakingId) REFERENCES stocktaking(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES user(id)
);

CREATE TABLE `item`(
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `position` INT NOT NULL,
    `barcode` VARCHAR(13) NOT NULL,
    `sectionId` INT NOT NULL,
    `userId` INT NOT NULL,
    FOREIGN KEY (sectionId) REFERENCES section(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (userId) REFERENCES user(id)
);