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
    `Email` VARCHAR(255) NOT NULL,
    `User` VARCHAR(20) DEFAULT NULL,
    `Senha` VARCHAR(100) NOT NULL,
    `Foto_Perfil` VARCHAR(255) DEFAULT NULL,
    `Data_Cadastro` DATETIME DEFAULT CURRENT_TIMESTAMP, 
    `Data_Alteracao` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;
/* drop table USUARIOS; */
/* select * from USUARIOS; */

-- -----------------------------------------------------
-- Table JOGOS
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS JOGOS (
    `ID_Jogo` INT AUTO_INCREMENT PRIMARY KEY,
    `Nome` VARCHAR(255) NOT NULL,
    `Descrição` TEXT DEFAULT NULL,
    `Curiosidades` TEXT DEFAULT NULL,
    `Caminho` VARCHAR(100) DEFAULT NULL,
    `Script` VARCHAR(100) DEFAULT NULL,
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
    FOREIGN KEY (`ID_Jogo`) REFERENCES `JOGOS` (`ID_Jogo`) ON DELETE CASCADE
) ENGINE = InnoDB;
/* drop table IMAGENS; */
/* select * from IMAGENS; */

-- -----------------------------------------------------
-- Table `COMENTARIOS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `COMENTARIOS` (
    `ID_Comentario` INT AUTO_INCREMENT PRIMARY KEY,
    `ID_Jogo` INT NOT NULL,
    `ID_Usuario` INT NOT NULL,
    `Texto` TEXT NOT NULL,
    `Data_Comentario` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`ID_Jogo`) REFERENCES `JOGOS` (`ID_Jogo`) ON DELETE CASCADE,
    FOREIGN KEY (`ID_Usuario`) REFERENCES `USUARIOS` (`ID_Usuario`) ON DELETE CASCADE
) ENGINE = InnoDB;
/* drop table COMENTARIOS; */
/* select * from COMENTARIOS; */