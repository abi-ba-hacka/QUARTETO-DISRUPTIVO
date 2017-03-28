-- Adminer 4.3.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  ` apellido` varchar(45) DEFAULT NULL,
  `dni` varchar(45) DEFAULT NULL,
  ` telefono` varchar(45) DEFAULT NULL,
  ` email` varchar(45) DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `facturas`;
CREATE TABLE `facturas` (
  `id_fctura` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `cliente` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_fctura`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `facturas_ibfk_1` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id_item` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) DEFAULT NULL,
  `producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `costo_unitario` decimal(10,0) DEFAULT NULL,
  `impuestos` decimal(10,0) DEFAULT NULL,
  `costo_total` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id_item`),
  KEY `factura` (`factura`),
  CONSTRAINT `items_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `facturas` (`id_fctura`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2017-03-28 18:23:16