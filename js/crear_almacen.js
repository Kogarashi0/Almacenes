let ubicacionCount = 1;

function agregarCampo() {
    if (ubicacionCount >= 5) {
        alert('No puedes agregar más de 5 ubicaciones.');
        return;
    }
    ubicacionCount++;
    const contenedor = document.getElementById('ubicaciones-container');
    const nuevoCampo = document.createElement('div');
    nuevoCampo.classList.add('ubicacion-field');
    nuevoCampo.innerHTML = `
        <input type="text" name="ubicaciones[]" placeholder="Ubicación ${ubicacionCount}" required>
        <input type="number" name="capacidades_min[]" placeholder="Capacidad mínima" required>
        <input type="number" name="capacidades_max[]" placeholder="Capacidad máxima" required>
        <button type="button" onclick="eliminarCampo(this)" class="remove-button">-</button>
    `;
    contenedor.appendChild(nuevoCampo);
}

function eliminarCampo(boton) {
    boton.parentElement.remove();
    ubicacionCount--;
}
