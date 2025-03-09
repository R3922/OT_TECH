use ottech;
CREATE TABLE `datos_servicios` (
  `id_clien` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom_clien` VARCHAR(255) NOT NULL,
  `fech_ing` DATE NOT NULL,
  `resp` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id_clien`)
);

