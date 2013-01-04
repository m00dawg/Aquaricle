ALTER TABLE WaterProfiles MODIFY COLUMN waterType ENUM('Freshwater', 'Saltwater', 'Other') NOT NULL;
ALTER TABLE Equipment MODIFY COLUMN active ENUM('Yes', 'No') NOT NULL DEFAULT 'Yes';
