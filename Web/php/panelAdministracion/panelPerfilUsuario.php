<div class="d-flex" style="height: 100vh;">
    <!-- Sidebar -->
    <div class="bg-dark text-white p-3" style="width: 250px;">
        <h3>Panel de Usuario</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Perfil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="#">Cerrar sesión</a>
            </li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="flex-grow-1 p-4">
        <!-- Perfil de Usuario -->
        <h2>Perfil de Usuario</h2>
        <form>
            <div class="row mb-3">
                <label for="name" class="col-sm-2 col-form-label">Nombre</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" value="Juan Pérez" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="email" class="col-sm-2 col-form-label">Correo Electrónico</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="email" value="juan.perez@example.com" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="phone" class="col-sm-2 col-form-label">Teléfono</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="phone" value="123-456-7890" readonly>
                </div>
            </div>
            <div class="row mb-3">
                <label for="address" class="col-sm-2 col-form-label">Dirección</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="address" value="Calle Ficticia 123, Ciudad, País" readonly>
                </div>
            </div>

            <!-- Botón de editar perfil -->
            <button type="button" class="btn btn-primary" id="editButton">Editar</button>
            <!-- Botón para guardar cambios (aparece al editar) -->
            <button type="submit" class="btn btn-success d-none" id="saveButton">Guardar</button>
        </form>
    </div>
</div>