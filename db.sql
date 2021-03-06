-- EasyVisit
-- @author   zgheb.com
-- @license  See EULA_READ_ME.txt
	
 
-- MySQL Script generated by MySQL Workbench
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema easyvisit
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `easyvisit` ;

-- -----------------------------------------------------
-- Schema easyvisit
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `easyvisit` DEFAULT CHARACTER SET utf8 ;
USE `easyvisit` ;

-- -----------------------------------------------------
-- Table `easyvisit`.`frontdeskemployee`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `easyvisit`.`frontdeskemployee` ;

CREATE TABLE IF NOT EXISTS `easyvisit`.`frontdeskemployee` (
  `frontdeskemployee_id` INT NOT NULL AUTO_INCREMENT,
  `frontdeskemployee_username` TINYTEXT NOT NULL,
  `frontdeskemployee_password` CHAR(128) NULL,
  `frontdeskemployee_salt` CHAR(128) NULL,
  `frontdeskemployee_creation` DATE NOT NULL,
  `frontdeskemployee_ldap` TINYINT(1) NOT NULL,
  `frontdeskemployee_admin` TINYINT(1) NOT NULL DEFAULT 0,
  `frontdeskemployee_active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`frontdeskemployee_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `easyvisit`.`visitorlist`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `easyvisit`.`visitorlist` ;

CREATE TABLE IF NOT EXISTS `easyvisit`.`visitorlist` (
  `visitorlist_id` INT NOT NULL AUTO_INCREMENT,
  `visitorlist_date` DATE NOT NULL,
  `visitorlist_frontdeskemployee_idfk` INT NULL,
  PRIMARY KEY (`visitorlist_id`),
  INDEX `visitorlist_frontdeskemployee_idfk_idx` (`visitorlist_frontdeskemployee_idfk` ASC),
  CONSTRAINT `visitorlist_frontdeskemployee_idfk`
    FOREIGN KEY (`visitorlist_frontdeskemployee_idfk`)
    REFERENCES `easyvisit`.`frontdeskemployee` (`frontdeskemployee_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `easyvisit`.`visitor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `easyvisit`.`visitor` ;

CREATE TABLE IF NOT EXISTS `easyvisit`.`visitor` (
  `visitor_id` INT NOT NULL AUTO_INCREMENT,
  `visitor_visitorlist_idfk` INT NOT NULL,
  `visitor_name` TINYTEXT NOT NULL,
  `visitor_surname` TINYTEXT NOT NULL,
  `visitor_idshown` TINYINT(1) NOT NULL,
  `visitor_identifier_idfk` INT,
  `visitor_company` TINYTEXT NOT NULL,
  `visitor_contactperson` TINYTEXT NOT NULL,
  `visitor_start` TINYTEXT NOT NULL,
  `visitor_end` TINYTEXT NULL,
  `visitor_signature` MEDIUMBLOB NOT NULL,
  `visitor_start_frontdeskemployee_idfk` INT NOT NULL,
  `visitor_end_frontdeskemployee_idfk` INT NULL,
  PRIMARY KEY (`visitor_id`),
  INDEX `visitor_identifier_idfk` (`visitor_identifier_idfk` ASC),
  INDEX `visitor_start_frontdeskemployee_idfk_idx` (`visitor_start_frontdeskemployee_idfk` ASC),
  INDEX `visitor_end_frontdeskemployee_idfk_idx` (`visitor_end_frontdeskemployee_idfk` ASC),
  CONSTRAINT `visitor_identifier_idfk`
    FOREIGN KEY (`visitor_identifier_idfk`)
    REFERENCES `easyvisit`.`identifier` (`identifier_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `visitor_start_frontdeskemployee_idfk`
    FOREIGN KEY (`visitor_start_frontdeskemployee_idfk`)
    REFERENCES `easyvisit`.`frontdeskemployee` (`frontdeskemployee_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `visitor_end_frontdeskemployee_idfk`
    FOREIGN KEY (`visitor_end_frontdeskemployee_idfk`)
    REFERENCES `easyvisit`.`frontdeskemployee` (`frontdeskemployee_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `easyvisit`.`identifier`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `easyvisit`.`identifier` ;

CREATE TABLE IF NOT EXISTS `easyvisit`.`identifier` (
  `identifier_id` INT NOT NULL AUTO_INCREMENT,
  `identifier_name` TINYTEXT NOT NULL,
  `identifier_description` TINYTEXT NOT NULL,
  `identifier_deleted` TINYINT(1),
  PRIMARY KEY (`identifier_id`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `easyvisit`.`device`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `easyvisit`.`device` ;

CREATE TABLE IF NOT EXISTS `easyvisit`.`device` (
  `device_id` INT NOT NULL AUTO_INCREMENT,
  `device_name` TINYTEXT NOT NULL,
  `device_secret` CHAR(128) NOT NULL,
  `device_salt` CHAR(128) NOT NULL,
  `device_registered` DATE NOT NULL,
  `device_frontdeskemployee_idfk` INT NOT NULL,
  `device_active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`device_id`),
  INDEX `device_frontdeskemployee_idfk_idx` (`device_frontdeskemployee_idfk` ASC),
  CONSTRAINT `device_frontdeskemployee_idfk`
    FOREIGN KEY (`device_frontdeskemployee_idfk`)
    REFERENCES `easyvisit`.`frontdeskemployee` (`frontdeskemployee_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `easyvisit`.`flag`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `easyvisit`.`flag` ;

CREATE TABLE IF NOT EXISTS `easyvisit`.`flag` (
  `flag_id` INT NOT NULL AUTO_INCREMENT,
  `flag_name` TINYTEXT NOT NULL,
  `flag_value` TINYINT(1) NOT NULL,
  PRIMARY KEY (`flag_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `easyvisit`.`checkinvisitor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `easyvisit`.`checkinvisitor` ;

CREATE TABLE IF NOT EXISTS `easyvisit`.`checkinvisitor` (
  `checkinvisitor_id` INT NOT NULL AUTO_INCREMENT,
  `checkinvisitor_name` TINYTEXT NOT NULL,
  `checkinvisitor_surname` TINYTEXT NOT NULL,
  `checkinvisitor_company` TINYTEXT NOT NULL,
  `checkinvisitor_contactperson` TINYTEXT NOT NULL,
  `checkinvisitor_signature` MEDIUMBLOB NOT NULL,
  `checkinvisitor_start` TINYTEXT NOT NULL,
  `checkinvisitor_date` DATE NOT NULL,
  PRIMARY KEY (`checkinvisitor_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `easyvisit`.`contactperson`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `easyvisit`.`contactperson` ;

CREATE TABLE IF NOT EXISTS `easyvisit`.`contactperson` (
  `contactperson_id` INT NOT NULL AUTO_INCREMENT,
  `contactperson_name` TINYTEXT NOT NULL,
  `contactperson_email` VARCHAR(60) NOT NULL DEFAULT '',
  `contactperson_mobile` VARCHAR(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`contactperson_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `easyvisit`.`reoccuringvisitor`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `easyvisit`.`reoccuringvisitor` ;

CREATE TABLE IF NOT EXISTS `easyvisit`.`reoccuringvisitor` (
  `reoccuringvisitor_id` INT NOT NULL AUTO_INCREMENT,
  `reoccuringvisitor_code` VARCHAR(20) NOT NULL,
  `reoccuringvisitor_name` TINYTEXT NOT NULL,
  `reoccuringvisitor_surname` TINYTEXT NOT NULL,
  `reoccuringvisitor_company` TINYTEXT NOT NULL,
  `reoccuringvisitor_contactperson` TINYTEXT NOT NULL,
  PRIMARY KEY (`reoccuringvisitor_id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `easyvisit`.`frontdeskemployee`
-- -----------------------------------------------------
START TRANSACTION;
USE `easyvisit`;
INSERT INTO `easyvisit`.`frontdeskemployee` (`frontdeskemployee_id`, `frontdeskemployee_username`, `frontdeskemployee_password`, `frontdeskemployee_salt`, `frontdeskemployee_creation`, `frontdeskemployee_ldap`, `frontdeskemployee_admin`, `frontdeskemployee_active`) VALUES (1, 'admin', 'cb0d77add199642b57eb9c45491277414e738213155330ec9731895a259354245ce28d3aa100b8a8427ca4035e51b889b38eb8b8aaf715b47c0374503ba5ade2', 'f05db65e326220d18a854bf093825d1e5e10bca5ee2ea324635ee2528ec97bccf80e2b549bbb99a0d489dd74a3f784e87e55c37c3030381dcf50e10b9d05ade8', '2017-01-01', 0, 1, 1);
INSERT INTO `easyvisit`.`frontdeskemployee` (`frontdeskemployee_id`, `frontdeskemployee_username`, `frontdeskemployee_password`, `frontdeskemployee_salt`, `frontdeskemployee_creation`, `frontdeskemployee_ldap`, `frontdeskemployee_admin`, `frontdeskemployee_active`) VALUES (999, 'autologin', '80o406l0jo2pet1152rn', 'z7c19xd5k8rmznrpxhko', '2017-01-01', 0, 0, 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `easyvisit`.`flag`
-- -----------------------------------------------------
START TRANSACTION;
USE `easyvisit`;
INSERT INTO `easyvisit`.`flag` (`flag_id`, `flag_name`, `flag_value`) VALUES (1, 'awaiting_input', false);

COMMIT;

