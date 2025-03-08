document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('ordenTrabajoForm');

    //form.addEventListener('submit', function (event) {
      /*  let idClien = document.getElementById('id_clien') ? document.getElementById('id_clien').value.trim() : '';

        if (idClien === '') {
            alert('Error: No se ha recibido un ID válido de cliente.');
            event.preventDefault();
            return;
        }

        if (!validarManoDeObra() || !validarRepuestos() || !validarMateriales()) {
            event.preventDefault();
            return;
        } */

        if (!validarManoDeObra() || !validarRepuestos() || !validarMateriales()) {
            event.preventDefault();
            return;
        }

        alert('Formulario enviado correctamente.');
    });

    document.querySelectorAll('input[name="tiempo[]"], select[name="tarifa[]"], select[name="und[]"]').forEach(elemento => {
        elemento.addEventListener('input', actualizarTotalesManoDeObra);
    });

    document.querySelectorAll('input[name="cant_rep[]"], input[name="pu_rep[]"], input[name="cant_mat[]"], input[name="pu_mat[]"]').forEach(elemento => {
        elemento.addEventListener('input', actualizarTotalesRepuestosYMateriales);
    });


// 📌 **FUNCIONES PARA OBTENER Y FORMATEAR VALORES**
function obtenerNumero(valor) {
    return parseFloat(valor.replace(',', '.')) || 0;
}

function formatearNumero(numero) {
    return numero.toFixed(2); // Mantiene el formato con punto decimal para los inputs numéricos
}

// 📌 **ACTUALIZAR TOTALES MANO DE OBRA**
function actualizarTotalesManoDeObra() {
    let filas = document.querySelectorAll('.section table tr');

    filas.forEach((fila, index) => {
        if (index === 0) return;

        let tiempoInput = fila.querySelector('input[name="tiempo[]"]');
        let tarifaSelect = fila.querySelector('select[name="tarifa[]"]');
        let unidadSelect = fila.querySelector('select[name="und[]"]');
        let totalInput = fila.querySelector('input[name="total[]"]');

        if (!tiempoInput || !tarifaSelect || !unidadSelect || !totalInput) return;

        let tiempo = obtenerNumero(tiempoInput.value);
        let tarifa = obtenerNumero(tarifaSelect.value);
        let unidad = unidadSelect.value;

        if (tiempo > 0 && tarifa > 0 && unidad) {
            let tiempoCalculado = unidad === 'min' ? tiempo / 60 : tiempo;
            let total = tiempoCalculado * tarifa;
            totalInput.value = total.toFixed(2).replace(',', '.');
            //totalInput.value = formatearNumero(total);
        } else {
            totalInput.value = '';
        }
    });

    actualizarResumenCostos();
}

// 📌 **ACTUALIZAR TOTALES REPUESTOS Y MATERIALES**
function actualizarTotalesRepuestosYMateriales() {
    let filasRepuestos = document.querySelectorAll('.section table tr');
    let filasMateriales = document.querySelectorAll('.section table tr');

    filasRepuestos.forEach((fila, index) => {
        if (index === 0) return;

        let cantidadInput = fila.querySelector('input[name="cant_rep[]"]');
        let precioInput = fila.querySelector('input[name="pu_rep[]"]');
        let totalInput = fila.querySelector('input[name="total_rep[]"]');

        if (!cantidadInput || !precioInput || !totalInput) return;

        let cantidad = obtenerNumero(cantidadInput.value);
        let precio = obtenerNumero(precioInput.value);

        if (cantidad > 0 && precio > 0) {
            let total = cantidad * precio;
            totalInput.value = formatearNumero(total);
        } else {
            totalInput.value = '';
        }
    });

    filasMateriales.forEach((fila, index) => {
        if (index === 0) return;

        let cantidadInput = fila.querySelector('input[name="cant_mat[]"]');
        let precioInput = fila.querySelector('input[name="pu_mat[]"]');
        let totalInput = fila.querySelector('input[name="total_mat[]"]');

        if (!cantidadInput || !precioInput || !totalInput) return;

        let cantidad = obtenerNumero(cantidadInput.value);
        let precio = obtenerNumero(precioInput.value);

        if (cantidad > 0 && precio > 0) {
            let total = cantidad * precio;
            totalInput.value = formatearNumero(total);
        } else {
            totalInput.value = '';
        }
    });

    actualizarResumenCostos();
}

// 📌 **ACTUALIZAR RESUMEN DE COSTOS**
function actualizarResumenCostos() {
    let subTotal = 0;

    document.querySelectorAll('input[name="total[]"], input[name="total_rep[]"], input[name="total_mat[]"]').forEach(input => {
        subTotal += obtenerNumero(input.value);
    });

    let ut = subTotal * 0.10; // 10% utilidad
    let iva = (subTotal + ut) * 0.19; // 19% IVA
    let total = subTotal + ut + iva;

    document.getElementById('sub_total').value = formatearNumero(subTotal);
    document.getElementById('ut').value = formatearNumero(ut);
    document.getElementById('iva').value = formatearNumero(iva);
    document.getElementById('total').value = formatearNumero(total);
}
