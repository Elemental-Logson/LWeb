<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Facturas</title>
    <!-- Enlace a Bootstrap desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Enlace a Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Incluir el archivo header.php con PHP -->
    <?php include('header.php'); ?>

    <div class="container mt-5">
        <h2 class="mb-4">Gestión de Facturas</h2>

        <!-- Tabla de facturas -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID Factura</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Monto</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Juan Pérez</td>
                    <td>2024-11-15</td>
                    <td>$150.00</td>
                    <td>
                        <button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Editar</button>
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Eliminar</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Ana Gómez</td>
                    <td>2024-11-14</td>
                    <td>$200.00</td>
                    <td>
                        <button class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Editar</button>
                        <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Botón para añadir nueva factura -->
        <button class="btn btn-success float-end" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">
            <i class="fas fa-plus"></i> Añadir Factura
        </button>
    </div>

    <!-- Modal para añadir nueva factura -->
    <div class="modal fade" id="addInvoiceModal" tabindex="-1" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addInvoiceModalLabel">Añadir Nueva Factura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <form action="add_invoice.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cliente" class="form-label">Cliente</label>
                            <input type="text" class="form-control" id="cliente" name="cliente" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripcion</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>
                        <div class="mb-3">
                            <label for="monto" class="form-label">Monto</label>
                            <input type="number" step="0.01" class="form-control" id="monto" name="monto" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Enlace a Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>