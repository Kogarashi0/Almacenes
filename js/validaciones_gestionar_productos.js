/**
 * Valida los campos de entrada antes de enviar el formulario.
 * @param {HTMLFormElement} form - Formulario a validar.
 * @returns {boolean} - Devuelve true si la validación es exitosa, de lo contrario false.
 */
function validarFormulario(form) {
    const tipo = form.querySelector("#tipo").value;
    const cantidad = form.querySelector("input[name='cantidad']")?.value;
    const errores = [];

    // Validar cantidad general
    if (!cantidad || isNaN(cantidad) || cantidad <= 0) {
        errores.push("La cantidad debe ser un número mayor a 0.");
    }

    // Asegurarse de que el tipo de movimiento está seleccionado
    if (!tipo) {
        errores.push("Debe seleccionar un tipo de movimiento.");
    }

    // Validaciones específicas por tipo de movimiento
    switch (tipo) {
        case "compra":
            validarCompra(form, errores);
            break;
        case "venta":
            validarVenta(form, errores);
            break;
        case "transferencia":
            validarTransferencia(form, errores);
            break;
        case "desecho":
            validarDesecho(form, errores);
            break;
        default:
            errores.push("El tipo de movimiento no es válido.");
    }

    // Mostrar errores si existen
    if (errores.length > 0) {
        alert("Errores de validación:\n" + errores.join("\n"));
        return false;
    }

    return true;
}

function validarCompra(form, errores) {
    const productoSeleccionado = form.querySelector("#select_producto")?.checked;
    const nuevoProducto = form.querySelector("#nuevo_producto_option")?.checked;

    if (productoSeleccionado) {
        const productoId = form.querySelector("#producto_id").value;
        if (!productoId) {
            errores.push("Debe seleccionar un producto existente para la compra.");
        }
    } else if (nuevoProducto) {
        const nuevoProductoNombre = form.querySelector("#nuevo_producto").value;
        if (!nuevoProductoNombre.trim()) {
            errores.push("Debe ingresar el nombre del nuevo producto.");
        }
    }

    const ubicacionDestino = form.querySelector("#ubicacion_destino_id").value;
    const capacidadMinima = parseInt(form.querySelector("#ubicacion_destino_min").value || 0, 10);
    const capacidadMaxima = parseInt(form.querySelector("#ubicacion_destino_max").value || 0, 10);
    const stockActual = parseInt(form.querySelector("#ubicacion_destino_stock").value || 0, 10);
    const cantidad = parseInt(form.querySelector("input[name='cantidad']").value || 0, 10);

    if (!ubicacionDestino) {
        errores.push("Debe seleccionar una ubicación de destino.");
    } else if (stockActual + cantidad > capacidadMaxima) {
        errores.push("No puede realizar la compra porque excede la capacidad máxima de la ubicación seleccionada.");
    } else if (stockActual + cantidad < capacidadMinima) {
        errores.push("El stock total tras la compra debe cumplir con la capacidad mínima de la ubicación.");
    }
}


function validarVenta(form, errores) {
    const productoId = form.querySelector("#producto_id").value;
    const ubicacionOrigen = form.querySelector("#ubicacion_origen_id").value;
    const stockActual = parseInt(form.querySelector("#ubicacion_origen_stock").value || 0, 10);
    const cantidad = parseInt(form.querySelector("input[name='cantidad']").value || 0, 10);

    if (!productoId) {
        errores.push("Debe seleccionar un producto para la venta.");
    }

    if (!ubicacionOrigen) {
        errores.push("Debe seleccionar una ubicación de origen.");
    } else if (stockActual < cantidad) {
        errores.push("No puede realizar la venta porque no hay suficiente stock en la ubicación seleccionada.");
    }
}

function validarTransferencia(form, errores) {
    const productoId = form.querySelector("#producto_id").value;
    const ubicacionOrigen = form.querySelector("#ubicacion_origen_id").value;
    const ubicacionDestino = form.querySelector("#ubicacion_destino_id").value;

    const stockOrigen = parseInt(form.querySelector("#ubicacion_origen_stock").value || 0, 10);
    const stockDestino = parseInt(form.querySelector("#ubicacion_destino_stock").value || 0, 10);
    const capacidadMaximaDestino = parseInt(form.querySelector("#ubicacion_destino_max").value || 0, 10);
    const cantidad = parseInt(form.querySelector("input[name='cantidad']").value || 0, 10);

    if (!productoId) {
        errores.push("Debe seleccionar un producto para la transferencia.");
    }

    if (!ubicacionOrigen) {
        errores.push("Debe seleccionar una ubicación de origen.");
    } else if (stockOrigen < cantidad) {
        errores.push("No puede realizar la transferencia porque no hay suficiente stock en la ubicación de origen.");
    }

    if (!ubicacionDestino) {
        errores.push("Debe seleccionar una ubicación de destino.");
    } else if (stockDestino + cantidad > capacidadMaximaDestino) {
        errores.push("No puede realizar la transferencia porque excede la capacidad máxima de la ubicación de destino.");
    }
}

function validarDesecho(form, errores) {
    const productoId = form.querySelector("#producto_id").value;
    const ubicacionOrigen = form.querySelector("#ubicacion_origen_id").value;
    const stockActual = parseInt(form.querySelector("#ubicacion_origen_stock").value || 0, 10);
    const cantidad = parseInt(form.querySelector("input[name='cantidad']").value || 0, 10);
    const razonDesecho = form.querySelector("#razon_desecho").value;

    if (!productoId) {
        errores.push("Debe seleccionar un producto para el desecho.");
    }

    if (!ubicacionOrigen) {
        errores.push("Debe seleccionar una ubicación de origen.");
    } else if (stockActual < cantidad) {
        errores.push("No puede realizar el desecho porque no hay suficiente stock en la ubicación seleccionada.");
    }

    if (!razonDesecho.trim()) {
        errores.push("Debe proporcionar una razón para el desecho.");
    }
}

// Exportar función para validación
if (typeof module !== "undefined" && module.exports) {
    module.exports = { validarFormulario };
}
