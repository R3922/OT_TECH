CREATE TABLE `resumen_costos` (
  `id_resu` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_clien` INT UNSIGNED NOT NULL,
  `sub_total` DECIMAL(10,2) NOT NULL,
  `ut` DECIMAL(10,2) NOT NULL,
  `iva` DECIMAL(10,2) NOT NULL,
  `total` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id_resu`),
  CONSTRAINT `fk_resumen_costos_servicio`
    FOREIGN KEY (`id_clien`)
    REFERENCES `datos_servicios` (`id_clien`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
