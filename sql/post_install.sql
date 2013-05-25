DROP DATABASE IF EXISTS aquaridawg;
CREATE DATABASE aquaridawg DEFAULT CHARACTER SET 'utf8';
USE aquaridawg;
CREATE TABLE `WaterProfiles` (
    `waterProfileID` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `waterType` ENUM('Freshwater', 'Saltwater', 'Other') NOT NULL,
    `name` varchar(24) NOT NULL UNIQUE,
    `temperature` smallint UNSIGNED,
    `pH` numeric(3, 1),
    `KH` smallint UNSIGNED
);

CREATE TABLE `Aquariums` (
    `aquariumID` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `waterProfileID` integer NOT NULL,
    `activeSince` datetime,
    `tankSize` smallint UNSIGNED NOT NULL,
    `name` varchar(24) NOT NULL UNIQUE,
    `location` varchar(24) NOT NULL
);
ALTER TABLE `Aquariums` ADD CONSTRAINT `waterProfileID_refs_waterProfileID_70167e78` FOREIGN KEY (`waterProfileID`) REFERENCES `WaterProfiles` (`waterProfileID`);
CREATE TABLE `WaterLog` (
    `waterLogID` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `aquariumID` integer NOT NULL,
    `testedOn` datetime NOT NULL,
    `temperature` smallint UNSIGNED,
    `ammonia` numeric(3, 2),
    `nitrites` numeric(3, 2),
    `nitrates` numeric(3, 2),
    `pH` numeric(3, 1),
    `KH` smallint UNSIGNED,
    `amountExchanged` smallint UNSIGNED,
    `comments` longtext NOT NULL
);
ALTER TABLE `WaterLog` ADD CONSTRAINT `aquariumID_refs_aquariumID_7024e925` FOREIGN KEY (`aquariumID`) REFERENCES `Aquariums` (`aquariumID`);
CREATE INDEX `Aquariums_4e67733a` ON `Aquariums` (`waterProfileID`);
CREATE INDEX `WaterLog_3926ad3f` ON `WaterLog` (`aquariumID`);

CREATE TABLE `Life` (
  `lifeID` int(11) NOT NULL AUTO_INCREMENT,
  `commonName` varchar(64) NOT NULL,
   kind enum('Fish', 'Crustacean', 'Plant', 'Coral'),
  `kingdom` enum('Animalia', 'Plantae', 'Fungi') NOT NULL,
  PRIMARY KEY (`lifeID`)
) ENGINE=InnoDB;

CREATE TABLE `AquariumLife` (
  `aquariumLifeID` int(11) NOT NULL AUTO_INCREMENT,
  `lifeID` int(11) NOT NULL,
  `aquariumID` int(11) NOT NULL,
  `dateAdded` datetime NOT NULL,
  `nickname` varchar(64) DEFAULT NULL,
  `source` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`aquariumLifeID`),
  KEY `al_lifeID_fk` (`lifeID`),
  KEY `al_aquariumID_fk` (`aquariumID`),
  CONSTRAINT `al_aquariumID_fk` FOREIGN KEY (`aquariumID`) REFERENCES `Aquariums` (`aquariumID`),
  CONSTRAINT `al_lifeID_fk` FOREIGN KEY (`lifeID`) REFERENCES `Life` (`lifeID`)
) ENGINE=InnoDB;

CREATE TABLE `LifeLog` (
    `lifeLogID` int NOT NULL AUTO_INCREMENT,
    `aquariumLifeID` int NOT NULL,
    `logDate` datetime NOT NULL,
    `logEntry` varchar(12) NOT NULL,
    PRIMARY KEY (`lifeLogID`),
    KEY `ll_aquariumLifeID_fk` (aquariumLifeID),
    KEY `logDate_idx` (logDate),
    CONSTRAINT `ll_aquariumLifeID_fk` FOREIGN KEY (`aquariumLifeID`) REFERENCES `AquariumLife` (`aquariumLifeID`)
) ENGINE=InnoDB;

COMMIT;
