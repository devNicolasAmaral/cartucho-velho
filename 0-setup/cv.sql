-- -----------------------------------------------------
-- Criando BATABASE `cv`
-- -----------------------------------------------------
CREATE DATABASE IF NOT EXISTS `cv` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cv`;
/* drop database cv; */

-- -----------------------------------------------------
-- Table `USUARIOS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `USUARIOS` (
    `ID_Usuario` INT AUTO_INCREMENT PRIMARY KEY,
    `Nome` VARCHAR(255) NOT NULL,
    `User` LONGTEXT DEFAULT NULL,
    `Senha` VARCHAR(100) NOT NULL UNIQUE,
    `Foto_Perfil` VARCHAR(255) DEFAULT NULL,
    `Data_Cadastro` DATETIME DEFAULT CURRENT_TIMESTAMP, 
    `Data_Alteracao` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;
/* drop table USUARIOS; */
/* select * from USUARIOS; */

-- -----------------------------------------------------
-- Table `JOGOS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `JOGOS` (
    `ID_Jogo` INT AUTO_INCREMENT PRIMARY KEY,
    `Nome` VARCHAR(255) NOT NULL,
    `Descrição` LONGTEXT DEFAULT NULL,
    `Email` VARCHAR(100) NOT NULL UNIQUE,
    `Foto_Index` VARCHAR(255) DEFAULT NULL,
    `Script` LONGTEXT DEFAULT NULL,
    `Data_Upload` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `Data_Alteracao` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;
/* drop table JOGOS; */
/* select * from JOGOS; */

-- -----------------------------------------------------
-- Table `IMAGENS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `IMAGENS` (
    `ID_Imagem` INT AUTO_INCREMENT PRIMARY KEY,
    `ID_Jogo` INT NOT NULL,
    `Caminho` VARCHAR(100) NOT NULL UNIQUE,
    `Email` VARCHAR(100) NOT NULL UNIQUE,
    FOREIGN KEY (`ID_Jogo`) REFERENCES `JOGOS` (`ID_Jogo`) ON DELETE CASCADE
) ENGINE = InnoDB;
/* drop table IMAGENS; */
/* select * from IMAGENS; */