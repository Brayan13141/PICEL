-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema picel3
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema picel3
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `picel3` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `picel3` ;

-- -----------------------------------------------------
-- Table `picel3`.`usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `picel3`.`usuarios` (
  `id_User` INT NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(100) NOT NULL,
  `contrasena` VARCHAR(250) NOT NULL,
  `tipo_us` ENUM('Admin', 'Estudiante', 'Docente') NOT NULL,
  PRIMARY KEY (`id_User`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `picel3`.`estudiantes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `picel3`.`estudiantes` (
  `id_Estudiante` INT NOT NULL AUTO_INCREMENT,
  `id_User` INT NULL DEFAULT NULL,
  `num_control` CHAR(9) NOT NULL,
  `nombre` VARCHAR(50) NOT NULL,
  `apellidoP` VARCHAR(50) NOT NULL,
  `apellidoM` VARCHAR(50) NOT NULL,
  `correo` VARCHAR(50) NOT NULL,
  `carrera` VARCHAR(50) NULL DEFAULT NULL,
  `semestre` INT NOT NULL,
  `num_celular` CHAR(10) NOT NULL,
  PRIMARY KEY (`id_Estudiante`),
  INDEX `FK_Estudiantes_Usuario` (`id_User` ASC) VISIBLE,
  CONSTRAINT `FK_Estudiantes_Usuario`
    FOREIGN KEY (`id_User`)
    REFERENCES `picel3`.`usuarios` (`id_User`))
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `picel3`.`docentes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `picel3`.`docentes` (
  `id_Docente` INT NOT NULL AUTO_INCREMENT,
  `id_User` INT NULL DEFAULT NULL,
  `nombre` VARCHAR(50) NOT NULL,
  `apellidoP` VARCHAR(50) NOT NULL,
  `apellidoM` VARCHAR(50) NOT NULL,
  `correo` VARCHAR(50) NOT NULL,
  `carrera` VARCHAR(50) NULL DEFAULT NULL,
  `num_celular` CHAR(10) NOT NULL,
  PRIMARY KEY (`id_Docente`),
  INDEX `FK_Docentes_Usuario` (`id_User` ASC) VISIBLE,
  CONSTRAINT `FK_Docentes_Usuario`
    FOREIGN KEY (`id_User`)
    REFERENCES `picel3`.`usuarios` (`id_User`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `picel3`.`evento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `picel3`.`evento` (
  `id_Evento` INT NOT NULL AUTO_INCREMENT,
  `id_Docente` INT NOT NULL,
  `periodo` VARCHAR(50) NOT NULL,
  `nombre` TEXT NOT NULL,
  `descrip` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_Evento`),
  INDEX `FK_Evento_Docente` (`id_Docente` ASC) VISIBLE,
  CONSTRAINT `FK_Evento_Docente`
    FOREIGN KEY (`id_Docente`)
    REFERENCES `picel3`.`docentes` (`id_Docente`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `picel3`.`tarea`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `picel3`.`tarea` (
  `id_Tarea` INT NOT NULL AUTO_INCREMENT,
  `nombre` TEXT NOT NULL,
  `descrip` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id_Tarea`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `picel3`.`actividades_asignadas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `picel3`.`actividades_asignadas` (
  `id_Actividades` INT NOT NULL AUTO_INCREMENT,
  `id_Estudiante` INT NOT NULL,
  `id_Evento` INT NOT NULL,
  `id_Tarea` INT NOT NULL,
  `fecha_ini` VARCHAR(30) NOT NULL,
  `fecha_fin` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`id_Actividades`),
  INDEX `FK_Actividades_Estudiantes` (`id_Estudiante` ASC) VISIBLE,
  INDEX `FK_Actividades_Evento` (`id_Evento` ASC) VISIBLE,
  INDEX `FK_Actividades_Tarea` (`id_Tarea` ASC) VISIBLE,
  CONSTRAINT `FK_Actividades_Estudiantes`
    FOREIGN KEY (`id_Estudiante`)
    REFERENCES `picel3`.`estudiantes` (`id_Estudiante`),
  CONSTRAINT `FK_Actividades_Evento`
    FOREIGN KEY (`id_Evento`)
    REFERENCES `picel3`.`evento` (`id_Evento`),
  CONSTRAINT `FK_Actividades_Tarea`
    FOREIGN KEY (`id_Tarea`)
    REFERENCES `picel3`.`tarea` (`id_Tarea`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
