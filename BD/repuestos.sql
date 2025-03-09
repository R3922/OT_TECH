CREATE TABLE `repuestos` (
  `id_repues` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_mano` INT UNSIGNED NOT NULL,
  `des_repues` VARCHAR(255) NOT NULL,
  `cant_rep` INT UNSIGNED NOT NULL,
  `pu_rep` DECIMAL(10,2) NOT NULL,
  `total_rep` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id_repues`),
  CONSTRAINT `fk_repues_mano_obra`
    FOREIGN KEY (`id_mano`)
    REFERENCES `mano_obra` (`id_mano`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

