-- MySQL Script generated by MySQL Workbench
-- Wed Feb 23 23:21:23 2022
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema ornamentacion
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema ornamentacion
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `ornamentacion` DEFAULT CHARACTER SET utf8 ;
USE `ornamentacion` ;

-- -----------------------------------------------------
-- Table `ornamentacion`.`Usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`Usuario` (
  `IdUsuario` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `documento` BIGINT(10) NOT NULL,
  `nombre` VARCHAR(245) NOT NULL,
  `telefono` VARCHAR(45) NOT NULL,
  `direccion` VARCHAR(9) NOT NULL,
  `roll` ENUM('administrador ', 'vendedor', 'proveedor') NOT NULL,
  `usuario` VARCHAR(145) NOT NULL,
  `contrasena` VARCHAR(240) NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  PRIMARY KEY (`IdUsuario`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`Factura`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`Factura` (
  `IdFactura` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `numeroFactura` INT NOT NULL,
  `nombreCliente` VARCHAR(85) NOT NULL,
  `cantidad` BIGINT(10) NOT NULL,
  `fecha` DATE NOT NULL,
  `estado` ENUM("Proceso", "Finalizada", "Anulada") NOT NULL,
  `valor` BIGINT(80) NOT NULL,
  `usuarioVendedor` BIGINT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`IdFactura`),
  INDEX `fk_Factura_Usuario1_idx` (`usuarioVendedor` ASC) ,
  CONSTRAINT `fk_Factura_Usuario1`
    FOREIGN KEY (`usuarioVendedor`)
    REFERENCES `ornamentacion`.`Usuario` (`IdUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`Abono`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`Abono` (
  `IdAbono` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `numoerAbono` INT NOT NULL,
  `descripcion` VARCHAR(400) NOT NULL,
  `fecha` DATE NOT NULL,
  `valor` BIGINT(8) NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  `Factura_IdFactura` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`IdAbono`),
  INDEX `fk_Abono_Factura1_idx` (`Factura_IdFactura` ASC) ,
  CONSTRAINT `fk_Abono_Factura1`
    FOREIGN KEY (`Factura_IdFactura`)
    REFERENCES `ornamentacion`.`Factura` (`IdFactura`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`Producto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`Producto` (
  `IdProducto` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipo` ENUM("Fabricacion", "Instalacion", "Producto") NOT NULL,
  `nombre` VARCHAR(245) NOT NULL,
  `stock` INT NOT NULL,
  `valor` BIGINT(80) NOT NULL,
  `material` VARCHAR(245) NOT NULL,
  `tamano` VARCHAR(245) NOT NULL,
  `diseno` VARCHAR(250) NOT NULL,
  `descripcion` VARCHAR(245) NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  PRIMARY KEY (`IdProducto`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`Proveedor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`Proveedor` (
  `IdProveedor` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `documento` BIGINT(10) NOT NULL,
  `nombre` VARCHAR(245) NOT NULL,
  `ciudad` VARCHAR(245) NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  PRIMARY KEY (`IdProveedor`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`MateriaPrima`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`MateriaPrima` (
  `idMateria` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `tipo` ENUM("Varilla", "Perfiles", "Marco", "Divisiones", "Angulo", "Pintura", "Anticorrosivo", "Lija") NOT NULL,
  `stock` INT NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  PRIMARY KEY (`idMateria`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`Fabricacion`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`Fabricacion` (
  `idFabricacion` TINYINT(4) UNSIGNED NOT NULL AUTO_INCREMENT,
  `numeroFabricacion` INT NOT NULL,
  `cantidad` INT NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  `MateriaPrima_idMateria` INT UNSIGNED NOT NULL,
  `Usuario_IdUsuario` BIGINT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`idFabricacion`),
  INDEX `fk_Fabricacion_MateriaPrima1_idx` (`MateriaPrima_idMateria` ASC) ,
  INDEX `fk_Fabricacion_Usuario1_idx` (`Usuario_IdUsuario` ASC) ,
  CONSTRAINT `fk_Fabricacion_MateriaPrima1`
    FOREIGN KEY (`MateriaPrima_idMateria`)
    REFERENCES `ornamentacion`.`MateriaPrima` (`idMateria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Fabricacion_Usuario1`
    FOREIGN KEY (`Usuario_IdUsuario`)
    REFERENCES `ornamentacion`.`Usuario` (`IdUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`OrdenCompra`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`OrdenCompra` (
  `idOrdenCompra` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cantidad` INT NOT NULL,
  `fabricacionId` TINYINT(4) UNSIGNED NOT NULL,
  `Factura_IdFactura` INT UNSIGNED NOT NULL,
  `Producto_IdProducto` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idOrdenCompra`),
  INDEX `fk_OrdenCompra_Fabricacion1_idx` (`fabricacionId` ASC) ,
  INDEX `fk_OrdenCompra_Factura1_idx` (`Factura_IdFactura` ASC) ,
  INDEX `fk_OrdenCompra_Producto1_idx` (`Producto_IdProducto` ASC) ,
  CONSTRAINT `fk_OrdenCompra_Fabricacion1`
    FOREIGN KEY (`fabricacionId`)
    REFERENCES `ornamentacion`.`Fabricacion` (`idFabricacion`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_OrdenCompra_Factura1`
    FOREIGN KEY (`Factura_IdFactura`)
    REFERENCES `ornamentacion`.`Factura` (`IdFactura`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_OrdenCompra_Producto1`
    FOREIGN KEY (`Producto_IdProducto`)
    REFERENCES `ornamentacion`.`Producto` (`IdProducto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`Pedidos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`Pedidos` (
  `idPedidos` TINYINT(3) ZEROFILL UNSIGNED NOT NULL AUTO_INCREMENT,
  `numeroPedido` INT NOT NULL,
  `nombre` VARCHAR(245) NOT NULL,
  `fechaPedido` DATE NOT NULL,
  `fechaEntrega` DATE NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  `Proveedor_IdProveedor` BIGINT(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`idPedidos`),
  INDEX `fk_Pedidos_Proveedor1_idx` (`Proveedor_IdProveedor` ASC) ,
  CONSTRAINT `fk_Pedidos_Proveedor1`
    FOREIGN KEY (`Proveedor_IdProveedor`)
    REFERENCES `ornamentacion`.`Proveedor` (`IdProveedor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`DetallePedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`DetallePedido` (
  `idDetallePedido` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `numeroDetallePedido` INT NOT NULL,
  `valor` INT NOT NULL,
  `cantidad` INT NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  `pedidosId` TINYINT(3) ZEROFILL UNSIGNED NOT NULL,
  `materiaPrimaId` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idDetallePedido`),
  INDEX `fk_DetallePedido_Pedidos1_idx` (`pedidosId` ASC) VISIBLE,
  INDEX `fk_DetallePedido_MateriaPrima1_idx` (`materiaPrimaId` ASC) ,
  CONSTRAINT `fk_DetallePedido_Pedidos1`
    FOREIGN KEY (`pedidosId`)
    REFERENCES `ornamentacion`.`Pedidos` (`idPedidos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_DetallePedido_MateriaPrima1`
    FOREIGN KEY (`materiaPrimaId`)
    REFERENCES `ornamentacion`.`MateriaPrima` (`idMateria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
