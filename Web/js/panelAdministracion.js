document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById("sidebar");
    const toggleButton = document.getElementById("toggle-sidebar");
    const toggleIcon = document.getElementById("toggle-icon");
    const mainContent = document.getElementById("main-content");

    // Manejador de evento para cambiar el tamaño de la ventana
    window.addEventListener("resize", () => {
        if (window.innerWidth >= 992) {
            // Si la pantalla es grande, asegúrate de que el sidebar esté visible
            sidebar.classList.remove("active");
            toggleIcon.classList.replace("lni-chevron-right", "lni-chevron-left");
        } else {
            // Si la pantalla es pequeña, ocultar el sidebar
            sidebar.classList.add("active");
            toggleIcon.classList.replace("lni-chevron-left", "lni-chevron-right");
        }
    });

    toggleButton.addEventListener("click", () => {
        sidebar.classList.toggle("active");

        // Cambiar la dirección de la flecha
        if (sidebar.classList.contains("active")) {
            toggleIcon.classList.replace("lni-chevron-left", "lni-chevron-right");
        } else {
            toggleIcon.classList.replace("lni-chevron-right", "lni-chevron-left");
        }
    });
});

// Función para cargar contenido HTML externo en main-content
function loadContent(url) {
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error("Error al cargar el contenido");
            }
            return response.text();
        })
        .then(html => {
            document.getElementById("main-content").innerHTML = html;
        })
        .catch(error => {
            console.error(error);
            document.getElementById("main-content").innerHTML = "<p>Error al cargar el contenido</p>";
        });
}

// Añadir listeners a cada enlace del sidebar
document.getElementById("dashboard-link").addEventListener("click", function(event) {
    event.preventDefault();
    loadContent("../php/panelAdministracion/panelDashboard.php"); // Ruta al HTML de Dashboard
});

document.getElementById("usuarios-link").addEventListener("click", function(event) {
    event.preventDefault();
    loadContent("../php/panelAdministracion/panelUsuarios.php"); // Ruta al HTML de Usuarios
});

document.getElementById("escaner-link").addEventListener("click", function(event) {
    event.preventDefault();
    loadContent("../php/panelAdministracion/panelEscaner.php"); // Ruta al HTML de Escaner
});

document.getElementById("configuracion-link").addEventListener("click", function(event) {
    event.preventDefault();
    loadContent("../php/panelAdministracion/panelConfiguracion.php"); // Ruta al HTML de Configuración
});

document.addEventListener("DOMContentLoaded", function () {
    // Función para cargar contenido HTML externo en main-content
    function loadContent(url) {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error("Error al cargar el contenido");
                }
                return response.text();
            })
            .then(html => {
                document.getElementById("main-content").innerHTML = html;
            })
            .catch(error => {
                console.error(error);
                document.getElementById("main-content").innerHTML = "<p>Error al cargar el contenido</p>";
            });
    }
    // Gestión de la selección en el sidebar
    function setActiveLink(link) {
        // Remover la clase active de todos los enlaces
        const links = document.querySelectorAll("#sidebar .nav-link");
        links.forEach(item => item.classList.remove("active"));

        // Agregar la clase active al enlace clickeado
        link.classList.add("active");
    }
    // Añadir listeners a cada enlace del sidebar
    document.querySelectorAll("#sidebar .nav-link").forEach(link => {
        link.addEventListener("click", function (event) {
            event.preventDefault();
            setActiveLink(this); // Marcar como activo el enlace clickeado
            const url = this.getAttribute("data-url"); // Leer la URL desde data-url
            loadContent(url); // Cargar el contenido HTML
        });
    });
});
