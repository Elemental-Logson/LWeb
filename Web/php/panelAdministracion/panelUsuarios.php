<head>
    <link rel="stylesheet" href="../css/panelUsuarios.css">
</head>
<div class="container mt-5">
    <h1>Gestión de Usuarios</h1>
    <!-- Tabla de usuarios, envuelta en un contenedor responsivo -->

    <div class="table-responsive mt-4">
        <button type="button" class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-user-plus"></i> Añadir Usuario
        </button>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Juan Pérez</td>
                    <td>juanperez@example.com</td>
                    <td>
                        <button class="btn btn-warning btn-sm">Editar</button>
                        <button class="btn btn-danger btn-sm">Eliminar</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Ana Gómez</td>
                    <td>anagomez@example.com</td>
                    <td>
                        <button class="btn btn-warning btn-sm">Editar</button>
                        <button class="btn btn-danger btn-sm">Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Vista como tarjetas en pantallas móviles -->
    <div class="row mt-4">
        <div class="col-12 user-card">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Juan Pérez</h5>
                    <p class="card-text">juanperez@example.com</p>
                    <button class="btn btn-warning btn-sm">Editar</button>
                    <button class="btn btn-danger btn-sm">Eliminar</button>
                </div>
            </div>
        </div>
        <div class="col-12 user-card">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Ana Gómez</h5>
                    <p class="card-text">anagomez@example.com</p>
                    <button class="btn btn-warning btn-sm">Editar</button>
                    <button class="btn btn-danger btn-sm">Eliminar</button>
                </div>
            </div>
        </div>
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
                <form>
                    <div class="mb-3">
                        <label for="userName" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="userName" required>
                    </div>
                    <div class="mb-3">
                        <label for="userEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="userEmail" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Añadir Usuario</button>
                </form>
            </div>
        </div>
    </div>
</div>

<footer>
    <!-- Botón flotante para móvil (aparece solo en móviles) -->
    <button type="button" class="btn btn-success btn-mobile d-block d-sm-none" data-bs-toggle="modal"
        data-bs-target="#addUserModal">
        <i class="fas fa-user-plus"></i> Añadir Usuario
    </button>
</footer>