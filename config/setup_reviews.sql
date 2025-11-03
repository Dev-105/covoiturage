-- Add reviews table
CREATE TABLE IF NOT EXISTS `reviews` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `trip_id` INT,
    `reviewer_id` INT,
    `driver_id` INT,
    `rating` INT CHECK (rating BETWEEN 1 AND 5),
    `comment` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_review_trip` FOREIGN KEY (`trip_id`) REFERENCES `trips`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_review_reviewer` FOREIGN KEY (`reviewer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_review_driver` FOREIGN KEY (`driver_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `unique_review_per_trip` UNIQUE (`trip_id`, `reviewer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add average_rating column to users table
ALTER TABLE `users` 
ADD COLUMN IF NOT EXISTS `average_rating` DECIMAL(3,2) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `total_reviews` INT DEFAULT 0;