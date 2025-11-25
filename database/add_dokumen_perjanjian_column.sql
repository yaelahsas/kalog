-- Add dokumen_perjanjian column to facilities table
ALTER TABLE facilities ADD COLUMN dokumen_perjanjian VARCHAR(255) NULL AFTER no_perjanjian;