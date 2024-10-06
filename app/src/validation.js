

	//////////////////////////////////////////////////VALIDACIONES DEL REGISTRO////////////////////////////////////////////////////
	document.addEventListener('DOMContentLoaded', function() {
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
        const dniValido = /^\d{8}-[A-Z]$/; // Patrón ajustado para permitir el guion.
        if (!dniValido.test(dni) || !validarDNILetra(dni)) {
                valid = false;
                document.getElementById('dni_error').textContent = "El DNI no es válido o la letra no corresponde.";
        } else {
                document.getElementById('dni_error').textContent = "";
        }

        // Función para validar la letra del DNI
        function validarDNILetra(dni) {
            const letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
        
            // Eliminar el guion del DNI antes de calcular la letra
            const numero = parseInt(dni.substring(0, 8)); // Los primeros 8 caracteres son el número
            const letra = dni.charAt(9); // El décimo carácter (después del guion) es la letra

            const letraCorrecta = letras[numero % 23]; // Calculamos la letra correcta

            return letra === letraCorrecta; // Comparamos la letra
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
    
    //////////////////////////////////////////////////VALIDACIONES DE LA CANCION////////////////////////////////////////////////////
    document.getElementById('modify_item').addEventListener('submit', function(event) {   
        let valid = true;

        // Validación del nombre (solo letras mayúsculas y minúsculas)
        const cancion = document.getElementById('nombre_cancion').value;
        const nombreValido = /^[a-zA-Z\s]+$/;
        if (!nombreValido.test(cancion)) {
            valid = false;
            document.getElementById('nombre_error').textContent = "El nombre solo puede contener letras.";
        } else {
            document.getElementById('nombre_error').textContent = "";
        }

        // Si alguna validación falla, se previene el envío del formulario
        if (!valid) {
            event.preventDefault();
        }
    });
   
    
        // Validación del formulario de modificación de usuario
        document.getElementById('user_modify_form').addEventListener('submit', function(event) {
            let valid = true;
    
            // Validación del nuevo valor (solo texto si el campo es nombre o apellidos)
            const field = document.getElementById('field').value;
            const newValue = document.getElementById('new_value').value;
            const nombreValido = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
    
            if (field === 'nombre' || field === 'apellidos') {
                if (!nombreValido.test(newValue)) {
                    valid = false;
                    document.getElementById('new_value_error').textContent = "El " + field + " solo puede contener letras.";
                } else {
                    document.getElementById('new_value_error').textContent = "";
                }
            }
           
    
            // Si alguna validación falla, se previene el envío del formulario
            if (!valid) {
                event.preventDefault();
            }
        });
    });
