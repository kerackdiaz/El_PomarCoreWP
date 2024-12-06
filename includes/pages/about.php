<?php
if (!defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

function Pomar_core_about_page() {
    ?>
    <div class="wrap workshophub-about">
        <h1>Somos Inclup</h1>
        <p>WorkshopHub es un plugin desarrollado para la gestión y venta de talleres y cursos en WordPress. Este plugin facilita la creación, administración y seguimiento de talleres, permitiendo a los usuarios registrarse y participar en ellos de manera sencilla.</p>
        
        <h2>Características Principales</h2>
        <ul>
            <li>Creación y gestión de talleres personalizados.</li>
            <li>Configuración de tipos de taller.</li>
            <li>Gestión de inscripciones y participantes.</li>
            <li>Notificaciones por correo electrónico para inscripciones.</li>
            <li>Reportes y estadísticas de talleres e inscripciones.</li>
        </ul>
        
        <h2>Autor</h2>
        <p>WorkshopHub fue desarrollado por <a href="https://github.com/kerackdiaz" target="_blank" rel="nofollow">Kerack Diaz</a>, un desarrollador apasionado por crear soluciones eficientes y efectivas para la comunidad de WordPress.</p>
        
        <h2>Empresa</h2>
        <p>Este plugin es una creación de <a href="http://3mas1r.com/" target="_blank" rel="nofollow">3+1R</a>, una empresa dedicada a ofrecer soluciones tecnológicas innovadoras y de alta calidad.</p>
        
        <h2>Contacto</h2>
        <p>Si tienes alguna pregunta o necesitas soporte, no dudes en contactarnos a través de nuestro sitio web <a href="http://3mas1r.com/" target="_blank" rel="nofollow">3+1R</a>.</p>
    </div>
    <?php
}