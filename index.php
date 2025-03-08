<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éxito</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Estilos adicionales para mejorar la apariencia */
        .message-box {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo {
            max-width: 150px;
        }

        .ticket {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .ticket img {
            max-width: 50px;
            margin-right: 10px;
        }

        .message-box h2 {
            color: #0056b3; /* Título en azul */
        }

        .message-box h3 {
            color: #0056b3;
        }

        ul.cost-summary {
            list-style-type: none;
            padding: 0;
        }

        ul.cost-summary li {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        ul.cost-summary li strong {
            color: #333;
        }

        a.btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #0056b3;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        a.btn:hover {
            background-color: #004494;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo-container">
            <img src="logo.jpg" alt="Logotipo" class="logo">
        </div>
        <div class="message-box">
            <div class="ticket">
                <img src="vb.png" alt="Logrado">
                <h2>Orden de Trabajo Guardada</h2>
            </div>
            <p>La orden de trabajo ha sido guardada exitosamente.</p>
            <h3>Resumen de Costos</h3>
            <ul class="cost-summary">
                <li><strong>Subtotal:</strong> <?php echo htmlspecialchars($_GET['subtotal']); ?></li>
                <li><strong>Utilidad:</strong> <?php echo htmlspecialchars($_GET['ut']); ?></li>
                <li><strong>IVA:</strong> <?php echo htmlspecialchars($_GET['iva']); ?></li>
                <li><strong>Total:</strong> <?php echo htmlspecialchars($_GET['total']); ?></li>
            </ul>
            <a href="index.html" class="btn">Volver al Inicio</a>
        </div>
    </div>
</body>

</html>
