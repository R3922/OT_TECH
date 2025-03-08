<?php
// Iniciar buffer de salida para evitar errores de FPDF
ob_start();

require('fpdf/fpdf.php');
require('conexion.php'); // Archivo de conexión a la base de datos

// Obtener fechas desde el formulario
$fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : null;
$fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : null;

if (!$fecha_desde || !$fecha_hasta) {
    die("Error: Debes proporcionar un rango de fechas.");
}

// Conectar a la base de datos
$conn = new mysqli("localhost", "root", "112233", "OTTECH");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$conn->set_charset("utf8"); // Asegurar que la conexión maneje caracteres latinos correctamente

// Consulta SQL mejorada
$sql = "
    SELECT 
    s.id_cli AS ID_Cliente,
    s.nom_cli AS Cliente,
    s.fech_ing AS Fecha_Ingreso,
    s.resp AS Responsable,

    e.pat AS Patente,
    e.marca AS Marca,
    e.model AS Modelo,
    e.anio AS Año,
    e.klm AS Kilometraje,

    mo.id_mano AS ID_Trabajo,
    mo.des_tra AS Descripcion_Trabajo,
    mo.tiempo AS Tiempo_Trabajo,
    mo.unidad AS Unidad,
    mo.tarifa AS Tarifa,

    r.id_repues AS ID_Repuesto,
    r.des_repues AS Descripcion_Repuesto,
    r.cant_rep AS Cantidad_Repuesto,
    r.pu_rep AS Precio_Unitario_Repuesto,

    m.id_material AS ID_Material,
    m.des_mat AS Descripcion_Material,
    m.cant_mat AS Cantidad_Material,
    m.pu_mat AS Precio_Unitario_Material,

    rc.sub_total AS Sub_Total,
    rc.ut AS Utilidad,
    rc.iva AS IVA,
    rc.total AS Total_Costos

FROM servicios s
INNER JOIN datos_equi e ON s.id_cli = e.id_cli
LEFT JOIN mano_obra mo ON s.id_cli = mo.id_cli
LEFT JOIN repuestos r ON s.id_cli = r.id_cli
LEFT JOIN materiales m ON s.id_cli = m.id_cli
LEFT JOIN resumen_costos rc ON s.id_cli = rc.id_cli

WHERE s.fech_ing BETWEEN '$fecha_desde' AND '$fecha_hasta'
ORDER BY s.id_cli, mo.id_mano, r.id_repues, m.id_material;

";

$result = $conn->query($sql);

// Crear PDF con FPDF
class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(190, 10, 'Reporte de Servicios', 0, 1, 'C');
        $this->Ln(5);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 10);

// Encabezados principales
$pdf->Cell(20, 8, "ID", 1);
$pdf->Cell(40, 8, "Cliente", 1);
$pdf->Cell(30, 8, "Fecha Ing.", 1);
$pdf->Cell(40, 8, "Responsable", 1);
$pdf->Cell(30, 8, "Patente", 1);
$pdf->Ln();

if ($result->num_rows > 0) {
    $pdf->SetFont('Arial', '', 9);
    $current_client = null;

    while ($row = $result->fetch_assoc()) {
        if ($row['ID_Cliente'] !== $current_client) {
            $current_client = $row['ID_Cliente'];

            $pdf->Cell(20, 8, $row['ID_Cliente'], 1);
            $pdf->Cell(40, 8, mb_convert_encoding($row['Cliente'], 'ISO-8859-1', 'UTF-8'), 1);
            $pdf->Cell(30, 8, $row['Fecha_Ingreso'], 1);
            $pdf->Cell(40, 8, mb_convert_encoding($row['Responsable'], 'ISO-8859-1', 'UTF-8'), 1);
            $pdf->Cell(30, 8, $row['Patente'], 1);
            $pdf->Ln();
        }

        // Trabajos realizados
        if ($row['ID_Trabajo']) {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 6, "Trabajo Realizado:", 1, 1, 'L');
            $pdf->SetFont('Arial', '', 9);
            $trabajo = $row['ID_Trabajo'] . ': ' . $row['Descripcion_Trabajo'] . ' (' . $row['Tiempo_Trabajo'] . ' ' . $row['Unidad'] . ') - ' . $row['Tarifa'] . '$';
            $pdf->MultiCell(190, 6, mb_convert_encoding($trabajo, 'ISO-8859-1', 'UTF-8'), 1);
        }

        // Repuestos usados
        if ($row['ID_Repuesto']) {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 6, "Repuestos Usados:", 1, 1, 'L');
            $pdf->SetFont('Arial', '', 9);
            $repuesto = $row['ID_Repuesto'] . ': ' . $row['Descripcion_Repuesto'] . ' (Cant: ' . $row['Cantidad_Repuesto'] . ') - ' . $row['Precio_Unitario_Repuesto'] . '$';
            $pdf->MultiCell(190, 6, mb_convert_encoding($repuesto, 'ISO-8859-1', 'UTF-8'), 1);
        }

        // Materiales utilizados
        if ($row['ID_Material']) {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(190, 6, "Materiales Utilizados:", 1, 1, 'L');
            $pdf->SetFont('Arial', '', 9);
            $material = $row['ID_Material'] . ': ' . $row['Descripcion_Material'] . ' (Cant: ' . $row['Cantidad_Material'] . ') - ' . $row['Precio_Unitario_Material'] . '$';
            $pdf->MultiCell(190, 6, mb_convert_encoding($material, 'ISO-8859-1', 'UTF-8'), 1);
        }
    }
} else {
    $pdf->Cell(190, 10, "No se encontraron resultados.", 1, 1, 'C');
}

// Limpiar buffer y generar PDF
ob_end_clean();
$pdf->Output();
$conn->close();
exit();
?>
