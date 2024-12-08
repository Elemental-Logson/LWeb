<head>
    <link rel="stylesheet" href="../css/panelUsuarios.css">
</head>
<div class="container mt-5">
    <h1>Gestión de Usuarios</h1>
    <!-- Contenedor para la tabla de usuarios -->
    <div class="table-responsive">
        <table class="table table-striped" id="userTable">
            <thead>
                <tr>
                    <th>#</th>
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
    <div class="row mt-4" id="userCards">
        <!-- Tarjetas de usuarios se insertarán aquí -->
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
                    </div>
                    <div class="mb-3">
                        <label for="userEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="userEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="contraseña" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="contraseña" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmarContraseña" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="confirmarContraseña" name="confirmPassword" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Añadir Usuario</button>
                </form>
                <div id="responseMessage" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

<script>
    loadUsers();

    // Función para cargar usuarios
    function loadUsers() {
        fetch('../php/CRUD/usuarios/getUsuario.php')
            .then(response => response.json())
            .then(data => {
                var userTableBody = document.querySelector('#userTable tbody');
                var userCardsContainer = document.querySelector('#userCards');

                // Limpiar contenido previo
                userTableBody.innerHTML = '';
                userCardsContainer.innerHTML = '';

                // Renderizar usuarios en la tabla
                data.forEach((user, index) => {
                    var row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${user.nombre}</td>
                        <td>${user.email}</td>
                        <td>
                            <button class="btn btn-primary btn-sm">Editar</button>
                            <button class="btn btn-danger btn-sm">Eliminar</button>
                            <button class="btn btn-warning btn-sm" onclick="disableUser('${user.id}')">Deshabilitar</button>
                        </td>
                    `;
                    userTableBody.appendChild(row);
                });

                // Renderizar usuarios en tarjetas para móviles
                data.forEach(user => {
                    var card = document.createElement('div');
                    card.className = 'col-md-4 mb-3';
                    card.innerHTML = `
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">${user.nombre}</h5>
                                <p class="card-text">${user.email}</p>
                                <button class="btn btn-primary btn-sm">Editar</button>
                                <button class="btn btn-danger btn-sm">Eliminar</button>
                                <button class="btn btn-warning btn-sm" onclick="disableUser('${user.id}')">Deshabilitar</button>
                            </div>
                        </div>
                    `;
                    userCardsContainer.appendChild(card);
                });
            })
            .catch(error => console.error('Error al cargar los usuarios:', error));
    }

    // Función para añadir un usuario
    function addUser() {
        // Capturar los datos del formulario
        var username = document.getElementById('userName').value;
        var email = document.getElementById('userEmail').value;
        var password = document.getElementById('contraseña').value;
        var confirmPassword = document.getElementById('confirmarContraseña').value;

        // Validar que las contraseñas coinciden
        if (password !== confirmPassword) {
            alert('Las contraseñas no coinciden');
            return;
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

                    // Cerrar el modal después de añadir el usuario
                    const addUserModal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                    addUserModal.hide();
                } else {
                    alert(`Error: ${data.message}`);
                }
            })
            .catch(error => console.error('Error al añadir el usuario:', error));
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
    // Asociar la función al evento de envío del formulario
    document.getElementById('addUserForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío predeterminado del formulario
        addUser();
    });

    // Botón para mostrar el menú para añadir usuarios
    var toggleMenuButton = document.createElement('button');
    toggleMenuButton.textContent = 'Añadir Usuario';
    toggleMenuButton.className = 'btn btn-success mb-3';
    toggleMenuButton.onclick = () => {
        var addUserModal = new bootstrap.Modal(document.getElementById('addUserModal'));
        addUserModal.show();
    };

    document.querySelector('.container').prepend(toggleMenuButton);
</script>