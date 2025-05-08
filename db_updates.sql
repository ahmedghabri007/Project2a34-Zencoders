-- Create users table
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add user_id and views columns to forum table if they don't exist
ALTER TABLE `forum` 
ADD COLUMN IF NOT EXISTS `user_id` int(11) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `views` int(11) NOT NULL DEFAULT 0;

-- Add foreign key constraint if it doesn't exist
SET @fk_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS 
                  WHERE CONSTRAINT_SCHEMA = '2a34' 
                  AND CONSTRAINT_NAME = 'fk_forum_user' 
                  AND CONSTRAINT_TYPE = 'FOREIGN KEY');

SET @sql = IF(@fk_exists = 0, 'ALTER TABLE `forum` ADD CONSTRAINT `fk_forum_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL', 'SELECT "Foreign key already exists"');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add user_id column to post_forum table if it doesn't exist
ALTER TABLE `post_forum` 
ADD COLUMN IF NOT EXISTS `user_id` int(11) DEFAULT NULL;

-- Add foreign key constraint if it doesn't exist
SET @fk_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS 
                  WHERE CONSTRAINT_SCHEMA = '2a34' 
                  AND CONSTRAINT_NAME = 'fk_post_user' 
                  AND CONSTRAINT_TYPE = 'FOREIGN KEY');

SET @sql = IF(@fk_exists = 0, 'ALTER TABLE `post_forum` ADD CONSTRAINT `fk_post_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL', 'SELECT "Foreign key already exists"');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Insert some sample users
INSERT INTO `users` (`username`, `role`, `linkedin_url`, `instagram_url`, `facebook_url`, `created_at`) VALUES
('John Doe', 'investor', 'https://linkedin.com/in/johndoe', 'https://instagram.com/johndoe', 'https://facebook.com/johndoe', NOW()),
('Jane Smith', 'entrepreneur', 'https://linkedin.com/in/janesmith', 'https://instagram.com/janesmith', 'https://facebook.com/janesmith', NOW());
