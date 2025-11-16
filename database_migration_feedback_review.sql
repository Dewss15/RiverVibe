-- ========================================
-- RiverVibe Feedback Review System Migration
-- Date: November 15, 2025
-- ========================================
-- This migration adds feedback review functionality
-- ========================================

USE rivervibe_db;

-- Add status column to feedback table
ALTER TABLE feedback 
ADD COLUMN status ENUM('Pending', 'Reviewing', 'Reviewed', 'Flagged') 
DEFAULT 'Pending' 
AFTER created_at;

-- Add admin_notes column to feedback table
ALTER TABLE feedback 
ADD COLUMN admin_notes TEXT NULL 
AFTER status;

-- Add index for better query performance
ALTER TABLE feedback 
ADD INDEX idx_status (status);

-- Update existing feedback records to have 'Pending' status
UPDATE feedback 
SET status = 'Pending' 
WHERE status IS NULL;

-- Verify the changes
DESCRIBE feedback;

SELECT 'Feedback Review System migration completed successfully!' AS status;
