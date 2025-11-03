-- SQL setup for the "covoiturage" database
-- Save this file as SQL (e.g. setup_database.sql) or run it in your MySQL client.

CREATE DATABASE IF NOT EXISTS `covoiturage` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `covoiturage`;

-- Users
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `first_name` VARCHAR(50),
    `last_name` VARCHAR(50),
    `email` VARCHAR(100) UNIQUE,
    `password` VARCHAR(255),
    `phone` VARCHAR(20),
    `role` ENUM('conducteur', 'passager'),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Trips
CREATE TABLE IF NOT EXISTS `trips` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `driver_id` INT,
    `departure_city` VARCHAR(100),
    `arrival_city` VARCHAR(100),
    `departure_date` DATE,
    `departure_time` TIME,
    `available_seats` INT,
    `price` DECIMAL(10,2),
    `description` TEXT,
    `status` ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_trips_driver` FOREIGN KEY (`driver_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Reservations
CREATE TABLE IF NOT EXISTS `reservations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `trip_id` INT,
    `passenger_id` INT,
    `seats_reserved` INT,
    `status` ENUM('confirmed', 'cancelled') DEFAULT 'confirmed',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_res_trip` FOREIGN KEY (`trip_id`) REFERENCES `trips`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_res_passenger` FOREIGN KEY (`passenger_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- End of SQL
