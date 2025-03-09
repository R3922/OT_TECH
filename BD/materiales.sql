CREATE TABLE materiales (
    id_material INT AUTO_INCREMENT PRIMARY KEY,
    id_cli INT NOT NULL,
    des_mat VARCHAR(255) NOT NULL,
    cant_mat INT NOT NULL,
    pu_mat INT NOT NULL,
    total_mat INT NOT NULL,
    FOREIGN KEY (id_cli) REFERENCES servicios(id_cli) ON DELETE CASCADE
);

SELECT * FROM materiales;

SELECT 
    s.id_cli AS ID_Cliente,
    s.nom_cli AS Cliente,
    s.fech_ing AS Fecha_Ingreso,
    e.pat AS Patente,
    e.marca AS Marca,
    e.model AS Modelo,
    e.anio AS Año,
    e.klm AS Kilometraje,
    
    mo.id_mano AS ID_Trabajo,
    mo.des_tra AS Descripción_Trabajo,
    mo.tiempo AS Tiempo,
    mo.unidad AS Unidad,
    mo.tarifa AS Tarifa,
    mo.total AS Total_ManoObra,

    r.id_repues AS ID_Repuesto,
    r.des_repues AS Descripción_Repuesto,
    r.cant_rep AS Cantidad_Repuesto,
    r.pu_rep AS Precio_Unitario_Repuesto,
    r.tota_rep AS Total_Repuesto,

    m.id_material AS ID_Material,
    m.des_mat AS Descripción_Material,
    m.cant_mat AS Cantidad_Material,
    m.pu_mat AS Precio_Unitario_Material,
    m.total_mat AS Total_Material

FROM servicios s
LEFT JOIN datos_equi e ON s.id_cli = e.id_cli
LEFT JOIN mano_obra mo ON s.id_cli = mo.id_cli
LEFT JOIN repuestos r ON s.id_cli = r.id_cli
LEFT JOIN materiales m ON s.id_cli = m.id_cli

ORDER BY s.id_cli, mo.id_mano, r.id_repues, m.id_material;

