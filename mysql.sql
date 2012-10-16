DROP DATABASE IF EXISTS aquaridawg;
CREATE DATABASE aquaridawg DEFAULT CHARACTER SET 'utf8';

BEGIN;
CREATE TABLE `WaterProfiles` (
    `waterProfileID` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `waterType` smallint UNSIGNED NOT NULL,
    `name` varchar(24) NOT NULL UNIQUE,
    `temperature` smallint UNSIGNED,
    `pH` numeric(3, 1),
    `KH` smallint UNSIGNED
)
;
CREATE TABLE `Aquariums` (
    `aquariumID` integer AUTO_INCREMENT NOT NULL PRIMARY KEY,
    `waterProfileID` integer NOT NULL,
    `activeSince` datetime,
    `tankSize` smallint UNSIGNED NOT NULL,
    `name` varchar(24) NOT NULL UNIQUE,
    `location` varchar(24) NOT NULL
)
;
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
)
;
ALTER TABLE `WaterLog` ADD CONSTRAINT `aquariumID_refs_aquariumID_7024e925` FOREIGN KEY (`aquariumID`) REFERENCES `Aquariums` (`aquariumID`);
CREATE INDEX `Aquariums_4e67733a` ON `Aquariums` (`waterProfileID`);
CREATE INDEX `WaterLog_3926ad3f` ON `WaterLog` (`aquariumID`);
COMMIT;
