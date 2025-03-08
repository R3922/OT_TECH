<?php
require 'conexion.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Insertar cliente y servicio en 'datos_servicios'
    $nom_clien = trim($_POST['nom_clien']);
    $fech_ing = $_POST['fech_ing'];
    $resp = trim($_POST['resp']);

    $sqlServicio = "INSERT INTO datos_servicios (nom_clien, fech_ing, resp) VALUES (?, ?, ?)";
    $stmtServicio = $conn->prepare($sqlServicio);
    $stmtServicio->bind_param("sss", $nom_clien, $fech_ing, $resp);
    $stmtServicio->execute();
    $id_clien = $stmtServicio->insert_id;
    $stmtServicio->close();

    // Insertar datos del equipo usando id_clien generado
    $pat = trim($_POST['pat']);
    $marca = trim($_POST['marca']);
    $modelo = trim($_POST['model']);
    $anio = trim($_POST['anio']);
    $klm = trim($_POST['klm']);

    $sqlEquipo = "INSERT INTO datos_equi (id_clien, pat, marca, model, anio, klm) VALUES (?, ?, ?, ?, ?, ?)";
    $stmtEquipo = $conn->prepare($sqlEquipo);
    $stmtEquipo->bind_param("issssi", $id_clien, $pat, $marca, $modelo, $anio, $klm);
    $stmtEquipo->execute();
    $id_datos = $stmtEquipo->insert_id;
    $stmtEquipo->close();

    // Insertar Mano de Obra y obtener id_mano
    $id_mano = null;
    if (!empty($_POST['des_tra'])) {
        $sqlManoObra = "INSERT INTO mano_obra (id_datos, des_tra, tiempo, und, tarifa, total) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtManoObra = $conn->prepare($sqlManoObra);

        foreach ($_POST['des_tra'] as $key => $descripcion) {
            $descripcion = trim($descripcion);
            $tiempo = $_POST['tiempo'][$key];
            $unidad = $_POST['und'][$key];
            $tarifa = $_POST['tarifa'][$key];
            //$total = $_POST['total'][$key];
            $total = ($unidad === 'min' ? $tiempo / 60 : $tiempo) * $tarifa;
            
            // Debugging: Ver el valor de cada variable
            //echo "id_datos: $id_datos, des_tra: $descripcion, tiempo: $tiempo, und: $unidad, tarifa: $tarifa, total: $total\n";


            if (!empty($descripcion) && !empty($tiempo) && !empty($unidad) && !empty($tarifa)) {
                $stmtManoObra->bind_param("isdsdd", $id_datos, $descripcion, $tiempo, $unidad, $tarifa, $total);
                $stmtManoObra->execute();
                if (!$id_mano) {
                    $id_mano = $stmtManoObra->insert_id;
                }
            }
        }
        $stmtManoObra->close();
    }

    // Insertar Repuestos
    if ($id_mano && !empty($_POST['des_repues'])) {
        $sqlRepuestos = "INSERT INTO repuestos (id_mano, des_repues, cant_rep, pu_rep, total_rep) VALUES (?, ?, ?, ?, ?)";
        $stmtRepuestos = $conn->prepare($sqlRepuestos);

        foreach ($_POST['des_repues'] as $key => $descripcion) {
            $descripcion = trim($descripcion);
            $cantidad = $_POST['cant_rep'][$key];
            $precio_unitario = $_POST['pu_rep'][$key];
            $total = $_POST['total_rep'][$key];

            if (!empty($descripcion) && !empty($cantidad) && !empty($precio_unitario)) {
                $stmtRepuestos->bind_param("isidd", $id_mano, $descripcion, $cantidad, $precio_unitario, $total);
                $stmtRepuestos->execute();
            }
        }
        $stmtRepuestos->close();
    }

    // Insertar Materiales
    if ($id_mano && !empty($_POST['des_mat'])) {
        $sqlMateriales = "INSERT INTO materiales (id_mano, des_mat, cant_mat, pu_mat, total_mat) VALUES (?, ?, ?, ?, ?)";
        $stmtMateriales = $conn->prepare($sqlMateriales);

        foreach ($_POST['des_mat'] as $key => $descripcion) {
            $descripcion = trim($descripcion);
            $cantidad = $_POST['cant_mat'][$key];
            $precio_unitario = $_POST['pu_mat'][$key];
            $total = $_POST['total_mat'][$key];

            if (!empty($descripcion) && !empty($cantidad) && !empty($precio_unitario)) {
                $stmtMateriales->bind_param("isidd", $id_mano, $descripcion, $cantidad, $precio_unitario, $total);
                $stmtMateriales->execute();
            }
        }
        $stmtMateriales->close();
    }

    // Resumen de costos
    $consulta = "SELECT (SELECT IFNULL(SUM(total),0) FROM mano_obra WHERE id_datos = ?) +
                        (SELECT IFNULL(SUM(total_rep),0) FROM repuestos WHERE id_mano = ?) +
                        (SELECT IFNULL(SUM(total_mat),0) FROM materiales WHERE id_mano = ?) AS subtotal";
    $stmt = $conn->prepare($consulta);
    $stmt->bind_param("iii", $id_datos, $id_mano, $id_mano);
    $stmt->execute();
    $stmt->bind_result($subtotal);
    $stmt->fetch();
    $stmt->close();

    $ut = $subtotal * 0.10; // Utilidad 10%
    $iva = ($subtotal + $ut) * 0.19; // IVA 19%
    $totalFinal = $subtotal + $ut + $iva;

    // Guardar resumen costos
    $sqlResumenCostos = "INSERT INTO resumen_costos (id_clien, sub_total, ut, iva, total) VALUES (?, ?, ?, ?, ?)";
    $stmtResumen = $conn->prepare($sqlResumenCostos);
    $stmtResumen->bind_param("idddd", $id_clien, $subtotal, $ut, $iva, $totalFinal);
    $stmtResumen->execute();
    $stmtResumen->close();

    // Redireccionar a la página de éxito con los parámetros necesarios
    header("Location: index.php?success=true&subtotal=$subtotal&ut=$ut&iva=$iva&total=$totalFinal");
    exit();

} else {
    die("Acceso no permitido.");

}

?>

