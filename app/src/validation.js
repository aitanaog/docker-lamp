document.addEventListener('DOMContentLoaded', function() {
    // Validaciones del registro
    document.getElementById('register_form')?.addEventListener('submit', function(event) {
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

        // Si alguna validación falla, se previene el envío del formulario
        if (!valid) {
            event.preventDefault();
        }
    });

    // Validaciones para modificar usuario
    document.getElementById('user_modify_form').addEventListener('submit', function(event) {
        let valid = true;

        // Obtener el campo y el nuevo valor
        const field = document.getElementById('field').value;
        const newValue = document.getElementById('new_value').value.trim();

        // Validación según el campo que se está editando
        switch (field) {
            case 'nombre':
                const nombreValido = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
                if (!nombreValido.test(newValue)) {
                    valid = false;
                    alert("El nombre solo puede contener letras.");
                }
                break;

            case 'apellidos':
                const apellidosValido = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
                if (!apellidosValido.test(newValue)) {
                    valid = false;
                    alert("Los apellidos solo pueden contener letras.");
                }
                break;

            case 'dni':
                const dniValido = /^\d{8}-[A-Z]$/;
                if (!dniValido.test(newValue) || !validarDNILetra(newValue)) {
                    valid = false;
                    alert("El DNI no es válido o la letra no corresponde.");
                }
                break;

            case 'telefono':
                const telefonoValido = /^\d{9}$/;
                if (!telefonoValido.test(newValue)) {
                    valid = false;
                    alert("El teléfono debe contener 9 dígitos.");
                }
                break;

            case 'email':
                const emailValido = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
                if (!emailValido.test(newValue)) {
                    valid = false;
                    alert("El formato de email no es válido.");
                }
                break;

            case 'fecha_nacimiento':
                if (new Date(newValue) >= new Date()) {
                    valid = false;
                    alert("La fecha de nacimiento debe ser válida.");
                }
                break;

            default:
                break;
        }

        // Si la validación falla, prevenir el envío del formulario
        if (!valid) {
            event.preventDefault();
        }
    });

    // Función para validar la letra del DNI
    function validarDNILetra(dni) {
        const letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
        const numero = parseInt(dni.substring(0, 8)); // Los primeros 8 caracteres son el número
        const letra = dni.charAt(9); // El décimo carácter (después del guion) es la letra
        const letraCorrecta = letras[numero % 23]; // Calculamos la letra correcta
        return letra === letraCorrecta; // Comparamos la letra
    }
});

