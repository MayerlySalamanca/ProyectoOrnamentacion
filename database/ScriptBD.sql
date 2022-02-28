-- MySQL Script generated by MySQL Workbench
-- Sun Feb 27 22:53:14 2022
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
  `idUsuario` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `documento` BIGINT(10) NOT NULL,
  `nombre` VARCHAR(245) NOT NULL,
  `telefono` VARCHAR(45) NOT NULL,
  `direccion` VARCHAR(9) NOT NULL,
  `roll` ENUM('administrador ', 'vendedor', 'cliente') NOT NULL,
  `usuario` VARCHAR(145) NOT NULL,
  `contrasena` VARCHAR(240) NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  PRIMARY KEY (`idUsuario`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`Factura`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`Factura` (
  `idFactura` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `numeroFactura` VARCHAR(256) NOT NULL,
  `usuarioVendedor` BIGINT(10) UNSIGNED NOT NULL,
  `usuarioCliente` BIGINT(10) UNSIGNED NOT NULL,
  `fecha` DATE NOT NULL,
  `monto` FLOAT NOT NULL,
  `estado` ENUM("Proceso", "Finalizada", "Anulada") NOT NULL,
  PRIMARY KEY (`idFactura`),
  INDEX `fk_Factura_Usuario1_idx` (`usuarioVendedor` ASC) ,
  INDEX `fk_Factura_Usuario2_idx` (`usuarioCliente` ASC) ,
  CONSTRAINT `fk_Factura_Usuario1`
    FOREIGN KEY (`usuarioVendedor`)
    REFERENCES `ornamentacion`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Factura_Usuario2`
    FOREIGN KEY (`usuarioCliente`)
    REFERENCES `ornamentacion`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`Producto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`Producto` (
  `idProducto` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipo` ENUM("Fabricacion", "Instalacion", "Producto") NOT NULL,
  `nombre` VARCHAR(245) NOT NULL,
  `stock` INT NOT NULL,
  `valor` BIGINT(80) NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  PRIMARY KEY (`idProducto`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`Proveedor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`Proveedor` (
  `idProveedor` BIGINT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `documento` BIGINT(10) NOT NULL,
  `nombre` VARCHAR(245) NOT NULL,
  `ciudad` VARCHAR(245) NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  PRIMARY KEY (`idProveedor`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`MateriaPrima`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`MateriaPrima` (
  `idMateria` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `tipo` ENUM("Varilla", "Perfiles", "Marco", "Divisiones", "Angulo", "Pintura", "Anticorrosivo", "Lija") NOT NULL,
  `valor_venta` INT NOT NULL,
  `stock` INT NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  PRIMARY KEY (`idMateria`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`detalle_ventas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`detalle_ventas` (
  `idOrdenCompra` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ventas_id` INT UNSIGNED NOT NULL,
  `Producto_IdProducto` INT UNSIGNED NOT NULL,
  `cantidad` INT NOT NULL,
  `precio` INT NOT NULL,
  PRIMARY KEY (`idOrdenCompra`),
  INDEX `fk_OrdenCompra_Factura1_idx` (`ventas_id` ASC) ,
  INDEX `fk_OrdenCompra_Producto1_idx` (`Producto_IdProducto` ASC) ,
  CONSTRAINT `fk_OrdenCompra_Factura1`
    FOREIGN KEY (`ventas_id`)
    REFERENCES `ornamentacion`.`Factura` (`idFactura`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_OrdenCompra_Producto1`
    FOREIGN KEY (`Producto_IdProducto`)
    REFERENCES `ornamentacion`.`Producto` (`idProducto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`Compras`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`Compras` (
  `id` TINYINT(3) ZEROFILL UNSIGNED NOT NULL AUTO_INCREMENT,
  `numero_serie` VARCHAR(45) NOT NULL,
  `empleado_id` BIGINT(10) UNSIGNED NOT NULL,
  `provedor_id` BIGINT(10) UNSIGNED NOT NULL,
  `fecha_compra` DATE NOT NULL,
  `monto` VARCHAR(245) NOT NULL,
  `estado` ENUM("Proceso", "Finalizada", "Anulada") NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Pedidos_Proveedor1_idx` (`provedor_id` ASC) ,
  INDEX `fk_Pedidos_Usuario1_idx` (`empleado_id` ASC) ,
  CONSTRAINT `fk_Pedidos_Proveedor1`
    FOREIGN KEY (`provedor_id`)
    REFERENCES `ornamentacion`.`Proveedor` (`idProveedor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Pedidos_Usuario1`
    FOREIGN KEY (`empleado_id`)
    REFERENCES `ornamentacion`.`Usuario` (`idUsuario`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`detalle_compra`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`detalle_compra` (
  `id` BIGINT(10) UNSIGNED NOT NULL,
  `materia_id` INT UNSIGNED NOT NULL,
  `compra_id` TINYINT(3) ZEROFILL UNSIGNED NOT NULL,
  `cantidad` INT NOT NULL,
  `precio_venta` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_detalle_compra_Compras1_idx` (`compra_id` ASC) ,
  INDEX `fk_detalle_compra_MateriaPrima1_idx` (`materia_id` ASC) ,
  CONSTRAINT `fk_detalle_compra_Compras1`
    FOREIGN KEY (`compra_id`)
    REFERENCES `ornamentacion`.`Compras` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalle_compra_MateriaPrima1`
    FOREIGN KEY (`materia_id`)
    REFERENCES `ornamentacion`.`MateriaPrima` (`idMateria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `ornamentacion`.`detalle_materia`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `ornamentacion`.`detalle_materia` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `MateriaPrima_idMateria` INT UNSIGNED NOT NULL,
  `Producto_IdProducto` INT UNSIGNED NOT NULL,
  `cantidad` INT NOT NULL,
  `precio_venta` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_detalle_materia_MateriaPrima1_idx` (`MateriaPrima_idMateria` ASC) ,
  INDEX `fk_detalle_materia_Producto1_idx` (`Producto_IdProducto` ASC) ,
  CONSTRAINT `fk_detalle_materia_MateriaPrima1`
    FOREIGN KEY (`MateriaPrima_idMateria`)
    REFERENCES `ornamentacion`.`MateriaPrima` (`idMateria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalle_materia_Producto1`
    FOREIGN KEY (`Producto_IdProducto`)
    REFERENCES `ornamentacion`.`Producto` (`idProducto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
