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
function loadContent(url, element) {
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error("Error al cargar el contenido");
            }
            return response.text();
        })
        .then(html => {
            // Actualizar el contenido principal
            document.getElementById("main-content").innerHTML = html;

            // Marcar el enlace seleccionado como activo
            setActiveLink(element);
        })
        .catch(error => {
            console.error(error);
            document.getElementById("main-content").innerHTML = "<p>Error al cargar el contenido</p>";
        });
}

// Función para establecer el enlace activo
function setActiveLink(activeLink) {
    // Eliminar la clase 'active' de todos los enlaces
    const links = document.querySelectorAll("#sidebar .nav-link");
    links.forEach(link => {
        link.classList.remove("active");
    });

    // Añadir la clase 'active' al enlace clickeado
    activeLink.classList.add("active");
}

// Añadir listeners a cada enlace del sidebar
document.getElementById("dashboard-link").addEventListener("click", function (event) {
    event.preventDefault();
    loadContent("../php/panelAdministracion/panelDashboard.php", this); // Ruta al HTML de Dashboard
});

document.getElementById("usuarios-link").addEventListener("click", function (event) {
    event.preventDefault();
    loadContent("../php/panelAdministracion/panelUsuarios.php", this); // Ruta al HTML de Usuarios
});

document.getElementById("escaner-link").addEventListener("click", function (event) {
    event.preventDefault();
    loadContent("../php/panelAdministracion/panelEscaner.php", this); // Ruta al HTML de Escaner
});

document.getElementById("transacciones-link").addEventListener("click", function (event) {
    event.preventDefault();
    loadContent("../php/panelAdministracion/panelTransacciones.php", this); // Ruta al HTML de Transacciones
});
