//HACER TODAS LAS VALIDACIONES DE LOS DATOS EN ESTE FICHERO

document.getElementById('register_form').addEventListener('submit', function(event) {   
    let valid = true;

    // Validación del nombre (solo texto)
    const nombre = document.getElementById('nombre').value;
    const nombreValido = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
    if (!nombreValido.test(nombre)) {
        valid = false;
        document.getElementById('nombre_error').textContent = "El nombre solo puede contener letras.";
    } else {
        document.getElementById('nombre_error').textContent = "";
    }

    // Validación de apellidos (solo texto)
    const apellidos = document.getElementById('apellidos').value;
    if (!nombreValido.test(apellidos)) {
        valid = false;
        document.getElementById('apellidos_error').textContent = "Los apellidos solo pueden contener letras.";
    } else {
        document.getElementById('apellidos_error').textContent = "";
    }

    // Validación de DNI (formato 11111111Z y validación de letra)
    const dni = document.getElementById('dni').value;
    const dniValido = /^\d{8}-[A-Z]$/;
    if (!dniValido.test(dni) || !validarDNILetra(dni)) {
        valid = false;
        document.getElementById('dni_error').textContent = "El DNI no es válido o la letra no corresponde.";
    } else {
        document.getElementById('dni_error').textContent = "";
    }

    // Validación de teléfono (solo 9 dígitos)
    const telefono = document.getElementById('telefono').value;
    const telefonoValido = /^\d{9}$/;
    if (!telefonoValido.test(telefono)) {
        valid = false;
        document.getElementById('telefono_error').textContent = "El teléfono debe contener 9 dígitos.";
    } else {
        document.getElementById('telefono_error').textContent = "";
    }

    // Validación de la fecha de nacimiento (formato yyyy-mm-dd)
    const fechaNacimiento = document.getElementById('fecha_nacimiento').value;
    if (new Date(fechaNacimiento) >= new Date()) {
        valid = false;
        document.getElementById('fecha_nacimiento_error').textContent = "La fecha de nacimiento debe ser válida.";
    } else {
        document.getElementById('fecha_nacimiento_error').textContent = "";
    }

    // Validación de email (formato estándar de email)
    const email = document.getElementById('email').value;
    const emailValido = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailValido.test(email)) {
        valid = false;
        document.getElementById('email_error').textContent = "El formato de email no es válido.";
    } else {
        document.getElementById('email_error').textContent = "";
    }


    // Validar que las contraseñas coincidan
    const contrasenna=document.getElementById('contrasenna').value;
    const contrasenna2= document.getElementById('contrasenna2').value;
    
    if (contrasenna === contrasenna2) {
    } else {
        mensaje.textContent = "Las contraseñas no coinciden.";
    }
    
    
    // Si alguna validación falla, se previene el envío del formulario
    if (!valid) {
        event.preventDefault();
    }
});

// Función para validar la letra del DNI
function validarDNILetra(dni) {
    const letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
    const numero = parseInt(dni.substring(0, 8));
    const letra = dni.charAt(8);
    const letraCorrecta = letras[numero % 23];
    return letra === letraCorrecta;
}

