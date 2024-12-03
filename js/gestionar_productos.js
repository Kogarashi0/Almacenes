document.addEventListener("DOMContentLoaded", () => {
    const tipoSelect = document.getElementById("tipo");
    const dynamicFields = document.getElementById("dynamic-fields");

    const getUbicacionesOptions = () => {
        return ubicacionesData
            .map(
                (ubicacion) => `
                <option value="${ubicacion.id}">
                    ${ubicacion.almacen} - ${ubicacion.ubicacion}
                </option>`
            )
            .join("");
    };

    const getProductosOptions = () => {
        return productosData
            .map((producto) => `<option value="${producto.id}">${producto.nombre}</option>`)
            .join("");
    };

    const getProveedoresOptions = () => {
        return proveedoresData
            .map((proveedor) => `<option value="${proveedor.id}">${proveedor.nombre}</option>`)
            .join("");
    };

    const templates = {
        compra: `
            <label>Producto:</label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="producto_option" id="select_producto" value="select" checked>
                    Seleccionar Producto
                </label>
                <label>
                    <input type="radio" name="producto_option" id="nuevo_producto_option" value="new">
                    Producto Nuevo
                </label>
            </div>
            <div id="producto_select_fields">
                <label for="producto_id">Seleccionar Producto:</label>
                <select name="producto_id" id="producto_id">
                    <option value="">Seleccione...</option>
                    ${getProductosOptions()}
                </select>
            </div>
            <div id="producto_new_fields" style="display: none;">
                <label for="nuevo_producto">Nombre del Producto:</label>
                <input type="text" name="nuevo_producto" id="nuevo_producto">
            </div>

            <label>Proveedor:</label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="proveedor_option" id="select_proveedor" value="select" checked>
                    Seleccionar Proveedor
                </label>
                <label>
                    <input type="radio" name="proveedor_option" id="nuevo_proveedor_option" value="new">
                    Proveedor Nuevo
                </label>
            </div>
            <div id="proveedor_select_fields">
                <label for="proveedor_id">Seleccionar Proveedor:</label>
                <select name="proveedor_id" id="proveedor_id">
                    <option value="">Seleccione...</option>
                    ${getProveedoresOptions()}
                </select>
            </div>
            <div id="proveedor_new_fields" style="display: none;">
                <label for="nuevo_proveedor">Nombre del Proveedor:</label>
                <input type="text" name="nuevo_proveedor" id="nuevo_proveedor">
            </div>

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

    tipoSelect.addEventListener("change", (event) => {
        const selectedTipo = event.target.value;
        dynamicFields.innerHTML = templates[selectedTipo] || "";

        if (selectedTipo === "compra") {
            const productoOptionRadios = document.getElementsByName("producto_option");
            const proveedorOptionRadios = document.getElementsByName("proveedor_option");

            productoOptionRadios.forEach((radio) => {
                radio.addEventListener("change", toggleProductoFields);
            });
            proveedorOptionRadios.forEach((radio) => {
                radio.addEventListener("change", toggleProveedorFields);
            });
        }
    });

    const toggleProductoFields = () => {
        const selectFields = document.getElementById("producto_select_fields");
        const newFields = document.getElementById("producto_new_fields");
        const isNew = document.getElementById("nuevo_producto_option").checked;
        selectFields.style.display = isNew ? "none" : "block";
        newFields.style.display = isNew ? "block" : "none";
    };

    const toggleProveedorFields = () => {
        const selectFields = document.getElementById("proveedor_select_fields");
        const newFields = document.getElementById("proveedor_new_fields");
        const isNew = document.getElementById("nuevo_proveedor_option").checked;
        selectFields.style.display = isNew ? "none" : "block";
        newFields.style.display = isNew ? "block" : "none";
    };
});
