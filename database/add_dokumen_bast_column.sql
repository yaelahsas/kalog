-- Add dokumen_bast column to facilities table
ALTER TABLE facilities ADD COLUMN dokumen_bast VARCHAR(255) NULL AFTER dokumen_perjanjian;