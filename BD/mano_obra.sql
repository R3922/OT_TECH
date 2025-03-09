CREATE TABLE `mano_obra` (
  `id_mano` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_datos` INT UNSIGNED NOT NULL,
  `des_tra` VARCHAR(255) NOT NULL,
  `tiempo` DECIMAL(10,2) NOT NULL,
  `und` ENUM('min','hr') NOT NULL,
  `tarifa` DECIMAL(10,2) NOT NULL,
  `total` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id_mano`),
  CONSTRAINT `fk_mano_obra_datos_equi`
    FOREIGN KEY (`id_datos`)
    REFERENCES `datos_equi` (`id_datos`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

