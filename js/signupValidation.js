document.getElementById('signupForm').addEventListener('submit', function(e) {
    const username = document.getElementById('username').value.trim();
    const firstname = document.getElementById('firstname').value.trim();
    const lastname = document.getElementById('lastname').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirmPassword = document.getElementById('confirmPassword').value.trim();

    if (username === '' || firstname === '' || lastname === '' || password === '' || confirmPassword === '') {
        e.preventDefault();
        alert('Por favor, complete todos los campos.');
        return;
    }

    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Las contraseñas no coinciden.');
        return;
    }

    if (password.length < 6) {
        e.preventDefault();
        alert('La contraseña debe tener al menos 6 caracteres.');
        return;
    }
});
