CREATE TABLE `datos_equi` (
  `id_datos` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pat` VARCHAR(50),
  `marca` VARCHAR(50),
  `model` VARCHAR(50),
  `anio` VARCHAR(4),
  `klm` VARCHAR(10),
  `id_clien` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id_datos`),
  CONSTRAINT `fk_datos_equi_servicios`
    FOREIGN KEY (`id_clien`)
    REFERENCES `datos_servicios`(`id_clien`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
