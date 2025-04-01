// Seleccionar el botón y el elemento raíz
const modoVisualBtn = document.getElementById('modo-visual-btn');
const root = document.documentElement;

// Función para alternar el modo visual
modoVisualBtn.addEventListener('click', () => {
    // Alternar la clase "dark-mode" en el <html>
    root.classList.toggle('dark-mode');

    // Seleccionar el icono dentro del botón
    const icono = modoVisualBtn.querySelector('ion-icon');
    
    // Cambiar el icono según el modo actual
    if (root.classList.contains('dark-mode')) {
        icono.setAttribute('name', 'sunny-outline'); // Cambiar a icono de sol
    } else {
        icono.setAttribute('name', 'moon-outline'); // Cambiar a icono de luna
    }

    // Guardar la preferencia en localStorage
    localStorage.setItem('modoVisual', root.classList.contains('dark-mode') ? 'dark' : 'light');
});

// Aplicar el modo guardado al cargar la página
window.addEventListener('DOMContentLoaded', () => {
    const modoGuardado = localStorage.getItem('modoVisual');
    const icono = modoVisualBtn.querySelector('ion-icon'); // Seleccionar el icono

    if (modoGuardado === 'dark') {
        document.documentElement.classList.add('dark-mode'); // Aplicar modo oscuro
        icono.setAttribute('name', 'sunny-outline'); // Icono de sol
    } else {
        document.documentElement.classList.remove('dark-mode'); // Asegurar modo claro
        icono.setAttribute('name', 'moon-outline'); // Icono de luna
    }
});
