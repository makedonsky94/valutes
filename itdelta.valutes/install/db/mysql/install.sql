CREATE TABLE IF NOT EXISTS `itdelta_valutes` (
  `ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ACTIVE` CHAR NOT NULL DEFAULT 'Y',
  `NAME` VARCHAR(255) NOT NULL,
  `VALUE` DOUBLE UNSIGNED NULL DEFAULT 1,
  PRIMARY KEY (`ID`))
ENGINE = InnoDB;