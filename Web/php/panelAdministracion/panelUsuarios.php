<?php
if (!defined('ACCESO_PERMITIDO')) {
    // header('HTTP/1.0 403 Forbidden');
    // exit('No tienes permiso para acceder directamente a este archivo.');
    header("Location: /LWeb/Web/html/forbidden.html");
    exit();
}
?>
<div class="container mt-5">
    <!-- Contenedor flexible para el título y el botón -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestión de Usuarios</h1>
        <button class="btn btn-success" onclick="showAddUserModal()">Añadir Usuario</button>
    </div>
    <!-- Contenedor para la tabla de usuarios -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-striped" id="userTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Filas de usuarios se insertarán aquí -->
            </tbody>
        </table>
    </div>

    <!-- Contenedor para las tarjetas de usuarios en dispositivos móviles -->
    <div class="row mt-4 d-block d-md-none" id="userCards">
        <!-- Tarjetas de usuarios se insertarán aquí -->
    </div>
    <div class="d-block d-md-none mt-3 text-center" id="mobilePagination">
        <!-- Botones de paginación se generarán dinámicamente -->
    </div>
</div>


<!-- Modal para añadir un usuario -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Añadir Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm">
                    <div class="mb-3">
                        <label for="userName" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="userName" name="username" required>
                        <small id="userNameError" class="text-danger d-none">El nombre debe tener al menos 5 caracteres.</small>
                    </div>
                    <div class="mb-3">
                        <label for="userEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="userEmail" name="email" required>
                        <small id="emailError" class="text-danger d-none">El correo electrónico contiene caracteres no permitidos.</small>
                    </div>
                    <div class="mb-3">
                        <label for="contraseña" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="contraseña" name="password" required>
                        <small id="passwordError" class="text-danger d-none">La contraseña debe tener al menos 8 caracteres.</small>
                    </div>
                    <div class="mb-3">
                        <label for="confirmarContraseña" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="confirmarContraseña" name="confirmPassword" required>
                        <small id="confirmPasswordError" class="text-danger d-none">Las contraseñas no coinciden.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Añadir Usuario</button>
                </form>
                <div id="responseMessage" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // Variables globales para la paginación
    var currentPage = 1;
    var itemsPerPage = 3;
    var addUserModal = document.getElementById('addUserModal');
    // Función para cargar usuarios
    loadUsers();

    if (addUserModal) {
        addUserModal.addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('addUserForm');
            form.reset(); // Limpiar todos los campos del formulario
            document.querySelectorAll(".error-message").forEach(el => el.remove());
        });
    }
    // Función para cargar usuarios
    function loadUsers() {
        fetch('../php/CRUD/usuarios/getUsuario.php')
            .then(response => response.json())
            .then(data => {
                var userTableBody = document.querySelector('#userTable tbody');
                var userCardsContainer = document.querySelector('#userCards');

                // Limpiar contenido previo
                userTableBody.innerHTML = '';

                // Renderizar usuarios en la tabla
                data.forEach((user, index) => {
                    var row = document.createElement('tr');
                    row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${user.username}</td>
                    <td>${user.nombre}</td>
                    <td>${user.email}</td>
                    <td>
                        ${user.enabled ? 
                            `<button class="btn btn-warning btn-sm" onclick="disableUser('${user.id}')">Deshabilitar</button>` :
                            `<button class="btn btn-success btn-sm" onclick="enableUser('${user.id}')">Habilitar</button>`
                        }
                    </td>
                `;
                    userTableBody.appendChild(row);
                });

                // Renderizar usuarios con paginación en móviles
                showPage(currentPage, data);
            })
            .catch(error => console.error('Error al cargar los usuarios:', error));
    }

    // Función para mostrar una página específica de tarjetas
    function showPage(page, data) {
        var userCardsContainer = document.querySelector('#userCards');
        var paginationContainer = document.querySelector('#mobilePagination');
        var startIndex = (page - 1) * itemsPerPage;
        var endIndex = startIndex + itemsPerPage;

        // Limpiar contenido previo
        userCardsContainer.innerHTML = '';
        paginationContainer.innerHTML = '';

        // Mostrar tarjetas en la página actual
        var pageData = data.slice(startIndex, endIndex);
        pageData.forEach(user => {
            var card = document.createElement('div');
            card.className = 'col-md-4 mb-3';
            card.innerHTML = `
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">${user.username}</h5>
                    <p class="card-text">${user.email}</p>
                    ${user.enabled ? 
                        `<button class="btn btn-warning btn-sm" onclick="disableUser('${user.id}')">Deshabilitar</button>` :
                        `<button class="btn btn-success btn-sm" onclick="enableUser('${user.id}')">Habilitar</button>`
                    }
                </div>
            </div>
        `;
            userCardsContainer.appendChild(card);
        });

        // Crear botones de paginación
        const totalPages = Math.ceil(data.length / itemsPerPage);
        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.className = `btn btn-sm ${i === page ? 'btn-primary' : 'btn-secondary'} mx-1`;
            pageButton.textContent = i;
            pageButton.addEventListener('click', () => {
                currentPage = i;
                showPage(currentPage, data);
            });
            paginationContainer.appendChild(pageButton);
        }
    }

    // Función para deshabilitar un usuario
    function disableUser(userId) {
        if (!confirm('¿Estás seguro de que deseas deshabilitar este usuario?')) {
            return;
        }

        fetch('../php/CRUD/usuarios/deshabilitarUsuario.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    userId: userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Usuario deshabilitado correctamente');
                    loadUsers();
                } else {
                    alert(`Error: ${data.message}`);
                }
            })
            .catch(error => console.error('Error al deshabilitar el usuario:', error));
    }

    // Función para habilitar un usuario
    function enableUser(userId) {
        if (!confirm('¿Estás seguro de que deseas habilitar este usuario?')) {
            return;
        }

        fetch('../php/CRUD/usuarios/habilitarUsuario.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    userId: userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Usuario habilitado correctamente');
                    loadUsers();
                } else {
                    alert(`Error: ${data.message}`);
                }
            })
            .catch(error => console.error('Error al habilitar el usuario:', error));
    }

    // Función para añadir un usuario
    function addUser() {
        // Capturar los datos del formulario
        var username = document.getElementById('userName').value;
        var email = document.getElementById('userEmail').value;
        var password = document.getElementById('contraseña').value;
        var confirmPassword = document.getElementById('confirmarContraseña').value;

        // Obtener los elementos para mostrar errores
        var userNameError = document.getElementById('userNameError');
        var emailError = document.getElementById('emailError');
        var passwordError = document.getElementById('passwordError');
        var confirmPasswordError = document.getElementById('confirmPasswordError');

        // Inicializar el estado de validación
        let isValid = true;

        // Validar nombre de usuario
        if (username.length < 5) {
            userNameError.textContent = "El nombre debe tener al menos 5 caracteres.";
            userNameError.classList.remove('d-none');
            isValid = false;
        } else {
            userNameError.classList.add('d-none');
        }

        // Validar email
        if (!email.includes('@')) {
            emailError.textContent = "El correo electrónico no es válido.";
            emailError.classList.remove('d-none');
            isValid = false;
        } else {
            emailError.classList.add('d-none');
        }

        // Validar contraseña
        if (password.length < 8) {
            passwordError.textContent = "La contraseña debe tener al menos 8 caracteres.";
            passwordError.classList.remove('d-none');
            isValid = false;
        } else {
            passwordError.classList.add('d-none');
        }

        // Validar confirmación de contraseña
        if (password !== confirmPassword) {
            confirmPasswordError.textContent = "Las contraseñas no coinciden.";
            confirmPasswordError.classList.remove('d-none');
            isValid = false;
        } else {
            confirmPasswordError.classList.add('d-none');
        }

        if (!isValid) {
            return; // Detener el envío si hay errores
        }

        // Crear un objeto con los datos del formulario
        var userData = {
            username: username,
            email: email,
            password: password
        };

        // Enviar los datos al servidor
        fetch('../php/CRUD/usuarios/crearUsuario.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(userData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Usuario añadido correctamente');
                    loadUsers();

                    // Cerrar y limpiar el modal
                    const userModal = bootstrap.Modal.getInstance(document.getElementById('addUserForm'));
                    userModal.hide();
                    form.reset(); // Limpia los campos del formulario
                } else {
                    alert(`Error: ${data.message}`);
                }
            })
            .catch(error => console.error('Error al añadir el usuario:', error));
    }

    // Asociar la función al evento de envío del formulario
    document.getElementById('addUserForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío predeterminado del formulario
        addUser();
    });

    // Función para mostrar el modal de añadir usuario
    function showAddUserModal() {
        const addUserModal = new bootstrap.Modal(document.getElementById('addUserModal'));
        addUserModal.show();
    }

    // Cargar usuarios al iniciar
    loadUsers();
</script>