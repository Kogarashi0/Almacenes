document.addEventListener("DOMContentLoaded", () => {
    const tipoSelect = document.getElementById("tipo");
    const dynamicFields = document.getElementById("dynamic-fields");

    // Opciones para ubicaciones
    const getUbicacionesOptions = () => {
        return ubicacionesData
            .map(
                (ubicacion) => `
                <option value="${ubicacion.id}">
                    ${ubicacion.almacen} - ${ubicacion.ubicacion}
                </option>
            `
            )
            .join("");
    };

    // Plantillas dinámicas para cada tipo de movimiento
    const templates = {
        compra: `
            <label for="nuevo_producto">Nombre del Producto:</label>
            <input type="text" name="nuevo_producto" id="nuevo_producto" required>

            <label for="nuevo_proveedor">Nombre del Proveedor:</label>
            <input type="text" name="nuevo_proveedor" id="nuevo_proveedor" required>

            <label for="ubicacion_destino_id">Ubicación de Destino:</label>
            <select name="ubicacion_destino_id" id="ubicacion_destino_id">
                ${getUbicacionesOptions()}
            </select>
        `,
        venta: `
            <label for="ubicacion_origen_id">Ubicación de Origen:</label>
            <select name="ubicacion_origen_id" id="ubicacion_origen_id">
                ${getUbicacionesOptions()}
            </select>
        `,
        transferencia: `
            <label for="ubicacion_origen_id">Ubicación de Origen:</label>
            <select name="ubicacion_origen_id" id="ubicacion_origen_id">
                ${getUbicacionesOptions()}
            </select>

            <label for="ubicacion_destino_id">Ubicación de Destino:</label>
            <select name="ubicacion_destino_id" id="ubicacion_destino_id">
                ${getUbicacionesOptions()}
            </select>
        `,
        desecho: `
            <label for="ubicacion_origen_id">Ubicación de Origen:</label>
            <select name="ubicacion_origen_id" id="ubicacion_origen_id">
                ${getUbicacionesOptions()}
            </select>
        `,
    };

    // Actualizar campos dinámicos cuando cambia el tipo de movimiento
    tipoSelect.addEventListener("change", (event) => {
        const selectedTipo = event.target.value;
        dynamicFields.innerHTML = templates[selectedTipo] || "";
    });
});
