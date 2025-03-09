SELECT 
    ds.id_clien, ds.nom_clien, ds.fech_ing, ds.resp,  -- Datos del servicio
    de.pat, de.marca, de.model, de.anio, de.klm,     -- Datos del equipo
    mo.id_mano, mo.des_tra, mo.tiempo, mo.und, mo.tarifa, mo.total AS total_mano, -- Mano de obra
    r.id_repues, r.des_repues, r.cant_rep, r.pu_rep, r.total_rep, -- Repuestos
    m.id_mat, m.des_mat, m.cant_mat, m.pu_mat, m.total_mat, -- Materiales
    rc.sub_total, rc.ut, rc.iva, rc.total -- Resumen de costos
FROM datos_servicios ds
LEFT JOIN datos_equi de ON ds.id_clien = de.id_clien
LEFT JOIN mano_obra mo ON de.id_datos = mo.id_datos
LEFT JOIN repuestos r ON mo.id_mano = r.id_mano
LEFT JOIN materiales m ON mo.id_mano = m.id_mano
LEFT JOIN resumen_costos rc ON ds.id_clien = rc.id_clien
ORDER BY ds.id_clien;
