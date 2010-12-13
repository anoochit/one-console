SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `oneconsole` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `oneconsole` ;

-- -----------------------------------------------------
-- Table `oneconsole`.`user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oneconsole`.`user` (
  `user` VARCHAR(50) NOT NULL ,
  `email` VARCHAR(45) NOT NULL ,
  `sshkey` TEXT NULL ,
  PRIMARY KEY (`user`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `oneconsole`.`privileges`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oneconsole`.`privileges` (
  `id` INT NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `oneconsole`.`user_privileges`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oneconsole`.`user_privileges` (
  `user` VARCHAR(50) NOT NULL ,
  `id` INT NOT NULL ,
  PRIMARY KEY (`user`, `id`) ,
  INDEX `fk_user_has_privileges_privileges1` (`id` ASC) ,
  INDEX `fk_user_has_privileges_user` (`user` ASC) ,
  CONSTRAINT `fk_user_has_privileges_user`
    FOREIGN KEY (`user` )
    REFERENCES `oneconsole`.`user` (`user` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_privileges_privileges1`
    FOREIGN KEY (`id` )
    REFERENCES `oneconsole`.`privileges` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `oneconsole`.`template`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `oneconsole`.`template` (
  `id` INT NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `template` TEXT NOT NULL ,
  `user` VARCHAR(50) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_template_user1` (`user` ASC) ,
  CONSTRAINT `fk_template_user1`
    FOREIGN KEY (`user` )
    REFERENCES `oneconsole`.`user` (`user` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
